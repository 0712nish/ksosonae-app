<template>
  <div class="grid">
   <div class="table-wrap">
    <table>

      <colgroup>
        <col class="col-no">
        <col class="col-kubetsu">
        <col class="col-hinmoku">

        <col
          v-for="q in quantityColumns"
          :key="q.date"
          class="col-qty"
        >
      </colgroup>

      <thead>
        <tr>
          <th rowspan="2" class="no-header">No</th>
          <th rowspan="2" class="kubetsu-header">区別</th>
          <th rowspan="2" class="hinmoku-header">品目</th>
          <th :colspan="quantityColumns.length">数量</th>
        </tr>

        <tr>
          <template v-for="q in quantityColumns" :key="q.date">
            <th>{{ q.date }} 締切<br>午前中</th>
          </template>
        </tr>


      </thead>

      <draggable
        v-model="rows"
        item-key="no"
        tag="tbody"
        handle=".no-cell"
        @end="renumberRows"
      >
        <template #item="{ element: row, index: r }">
          <tr>
          <td
            class="no-cell"
            @contextmenu.prevent="openContextMenu($event, r)"
          >
            {{ row.no }}
          </td>
          <!-- 区別 -->
          <td class="kubetsu-cell">
            <select
              class="kubetsu-select"
              v-model="row.kubetsu"
              @keydown="handleKey($event, r, 0)"
              :ref="el => setRef(el, r, 0)"
            >
              <option value=""></option>
              <option v-for="opt in kubetsuOptions" :key="opt">{{ opt }}</option>
            </select>
          </td>

          <!-- 品目 -->
          <td class="hinmoku-cell">
            <input
              class="hinmoku-input"
              v-model="row.hinmoku"
              @keydown="handleKey($event, r, 1)"
              :ref="el => setRef(el, r, 1)"
            />
          </td>

          <!-- 数量 -->
          <template v-for="(q, qi) in quantityColumns" :key="q.date">
            <td class="qty-cell">
              <!-- 上段 -->
              <div class="row-top">
                <!-- ml -->
                <input
                  v-model="row.quantities[q.date].ml_value"
                  :disabled="!isEditable(q.date)"
                  :class="{ requiredCell: isRequired(row, 'ml_value') && isEditable(q.date) }"
                  @keydown="handleKey($event, r, getColIndex(qi, 0))"
                  :ref="el => setRef(el, r, getColIndex(qi, 0))"
                />
                <select
                  v-model="row.quantities[q.date].ml_unit"
                  :disabled="!isEditable(q.date)"
                  class="unit"
                  :class="{ requiredCell: isRequired(row, 'ml_value') && isEditable(q.date) }"
                  @keydown="handleKey($event, r, getColIndex(qi, 0, true))"
                  :ref="el => setRef(el, r, getColIndex(qi, 0, true))"
                >
                  <option v-for="u in unitOptions" :key="u">{{ u }}</option>
                </select>
                
                <!-- 本 -->
                <input
                  v-model="row.quantities[q.date].hon_value"
                  :disabled="!isEditable(q.date)"
                  :class="{ requiredCell: isRequired(row, 'hon_value') && isEditable(q.date) }"
                  @keydown="handleKey($event, r, getColIndex(qi, 1))"
                  :ref="el => setRef(el, r, getColIndex(qi, 1))"
                />
                <select
                  v-model="row.quantities[q.date].hon_unit"
                  :disabled="!isEditable(q.date)"
                  class="unit"
                  :class="{ requiredCell: isRequired(row, 'hon_unit') && isEditable(q.date) }"
                  @keydown="handleKey($event, r, getColIndex(qi, 1, true))"
                  :ref="el => setRef(el, r, getColIndex(qi, 1, true))"
                >
                  <option v-for="u in unitOptions" :key="u">{{ u }}</option>
                </select>
              </div>

              <!-- 下段 -->
              <div class="row-bottom">
                <!-- 箱 -->
                <input
                  v-model="row.quantities[q.date].hako_value"
                  :disabled="!isEditable(q.date)"
                  :class="{ requiredCell: isRequired(row, 'hako_value') && isEditable(q.date) }"
                  @keydown="handleKey($event, r, getColIndex(qi, 2))"
                  :ref="el => setRef(el, r, getColIndex(qi, 2))"
                />
                <select
                  v-model="row.quantities[q.date].hako_unit"
                  :disabled="!isEditable(q.date)"
                  class="unit"
                  :class="{ requiredCell: isRequired(row, 'hako_value') && isEditable(q.date) }"
                  @keydown="handleKey($event, r, getColIndex(qi, 2, true))"
                  :ref="el => setRef(el, r, getColIndex(qi, 2, true))"
                >
                  <option v-for="u in unitOptions" :key="u">{{ u }}</option>
                </select>
              </div>
            </td>
          </template>
          </tr>
        </template>
      </draggable>
    </table>

<div
  v-if="menu.visible"
  class="context-menu"
  :style="{
    top: menu.y + 'px',
    left: menu.x + 'px'
  }"
>
  <div class="menu-item" @click="insertRow(menu.rowIndex)">
    行挿入
  </div>

  <div class="menu-item danger" @click="confirmDelete(menu.rowIndex)">
    行削除
  </div>
</div>

   </div> 
  </div>
</template>

<script setup>
import axios from "axios"
import { onMounted } from "vue"
import { ref, nextTick } from "vue"

import { useRoute } from "vue-router"
import draggable from "vuedraggable"

const route = useRoute()

const sname = route.query.sname
const mode = route.query.mode

/* ===== 定義 ===== */
const quantityColumns = [
  { date: "20日" },
  { date: "25日" },
  { date: "28日" },
]

const kubetsuOptions = ["野菜", "果物", "特産", "お米"]
const unitOptions = ["ml", "本", "箱", "kg", "g","袋","個"]

const menu = ref({
  visible: false,
  x: 0,
  y: 0,
  rowIndex: null
})
function openContextMenu(e, rowIndex) {
  menu.value.visible = true
  menu.value.x = e.clientX
  menu.value.y = e.clientY
  menu.value.rowIndex = rowIndex
}
function insertRow(index) {
  rows.value.splice(index, 0, createRow(index + 1))

  renumberRows()

  menu.value.visible = false
}
function confirmDelete(index) {
  menu.value.visible = false

  if (!confirm((index + 1) + "行目を削除しますか？")) {
    return
  }

  rows.value.splice(index, 1)

  renumberRows()
}
function renumberRows() {
  rows.value.forEach((row, i) => {
    row.no = i + 1
  })
}

/* ===== 行生成 ===== */
function createRow(no) {
  const quantities = {}

  quantityColumns.forEach(q => {
    quantities[q.date] = {
      ml_value: "",
      ml_unit: "",
      hon_value: "",
      hon_unit: "kg",
      hako_value: "",
      hako_unit: "",
    }
  })

  return {
    no,
    kubetsu: "",
    hinmoku: "",
    quantities
  }
}

const rows = ref([createRow(1)])

/* ===== セル参照 ===== */
const cellRefs = ref([])

function setRef(el, r, c) {
  if (!cellRefs.value[r]) cellRefs.value[r] = []
  cellRefs.value[r][c] = el
}

function focusCell(r, c) {
  nextTick(() => {
    cellRefs.value[r]?.[c]?.focus()
  })
}

/* ===== 行操作 ===== */
function addRow() {
  rows.value.push(createRow(rows.value.length + 1))
}

function deleteRow(r) {
  if (rows.value.length === 1) return
  rows.value.splice(r, 1)
  focusCell(Math.max(0, r - 1), 0)
}

/* ===== 列制御 ===== */
function getColIndex(qi, inner, isUnit = false) {
  return 2 + qi * 6 + inner * 2 + (isUnit ? 1 : 0)
}

const totalCols = 2 + quantityColumns.length * 6

/* ===== 移動処理 ===== */
function moveNext(r, c) {
  let nr = r
  let nc = c

  while (true) {
    nc++

    if (nc >= totalCols) {
      nc = 0
      nr++

      if (!rows.value[nr]) {
        addRow()
      }
    }

    /*if (isEditableCell(nc)) {*/
    if (isMovableCell(rows.value[nr], nc)) {
      break
    }
  }

  focusCell(nr, nc)
}

function movePrev(r, c) {
  let nr = r
  let nc = c

  while (true) {
    nc--

    if (nc < 0) {
      nr--

      if (nr < 0) {
        return
      }

      nc = totalCols - 1
    }

    if (isEditableCell(nc)) {
    /*if (isMovableCell(rows.value[nr], nc)) {*/
      break
    }
  }

  focusCell(nr, nc)
}

function moveVertical(r, c, dir) {
  if (!isEditableCell(c)) {
    return
  }

  let nr = r + dir

  if (nr < 0) {
    return
  }

  if (!rows.value[nr]) {
    addRow()
  }

  focusCell(nr, c)
}

/* ===== キー操作 ===== */
function handleKey(e, r, c) {
  const tag = e.target.tagName?.toLowerCase()
  const isSelect = tag === "select"

  if (e.key === "Enter") {
    e.preventDefault()
    e.shiftKey ? movePrev(r, c) : moveNext(r, c)
  }

  if (e.key === "Tab") {
    e.preventDefault()
    e.shiftKey ? movePrev(r, c) : moveNext(r, c)
  }

  if (e.key === "ArrowRight" && !isSelect) {
    e.preventDefault()
    moveNext(r, c)
  }

  if (e.key === "ArrowLeft" && !isSelect) {
    e.preventDefault()
    movePrev(r, c)
  }

  if (e.key === "ArrowDown" && !isSelect) {
    e.preventDefault()
    moveVertical(r, c, 1)
  }

  if (e.key === "ArrowUp" && !isSelect) {
    e.preventDefault()
    moveVertical(r, c, -1)
  }
/*
  if (e.key === "Delete") {
    e.preventDefault()
    deleteRow(r)
  }
    */
}

function setRowsFromDB(data) {
  rows.value = data.map(d => {
    return {
      no: d.no,
      kubetsu: d.oname,
      hinmoku: d.hinmoku,
      quantities: {
        "20日": {
          ml_value: trimZero(d.tanikosu1),
          ml_unit: d.tstani1,
          hon_value: trimZero(d.gokeisu1),
          hon_unit: d.gstani1,
          hako_value: trimZero(d.hakosu1),
          hako_unit: d.hstani1,
        },
        "25日": {
          ml_value: trimZero(d.tanikosu2),
          ml_unit: d.tstani2,
          hon_value: trimZero(d.gokeisu2),
          hon_unit: d.gstani2,
          hako_value: trimZero(d.hakosu2),
          hako_unit: d.hstani2,
        },
        "28日": {
          ml_value: trimZero(d.tanikosu3),
          ml_unit: d.tstani3,
          hon_value: trimZero(d.gokeisu3),
          hon_unit: d.gstani3,
          hako_value: trimZero(d.hakosu3),
          hako_unit: d.hstani3,
        }
      }
    }
  })
}
function trimZero(val) {
  if (val === null || val === undefined || val === "") {
    return ""
  }

  return parseFloat(val).toString()
}

function isRequired(row, field) {
  const map = {
    "野菜": ["hon_value", "hon_unit"],
    "果物": ["hon_value", "hon_unit"],
    "特産": [
      "ml_value",
      "ml_unit",
      "hon_value",
      "hon_unit",
      "hako_value",
      "hako_unit"
    ],
    "お米": ["hon_value", "hon_unit"]
  }

  return map[row.kubetsu]?.includes(field)
}

function isEditable(date) {
  if (mode === "ALL") {
    return true
  }

  return mode === date.replace("日", "")
}

function isEditableCell(c) {
  // No, 区別, 品目 は常に編集可
  if (c <= 1) {
    return true
  }

  // 数量列開始
  const quantityIndex = c - 2

  // 1日あたり6列
  const block = Math.floor(quantityIndex / 6)

  const date = quantityColumns[block]?.date

  if (!date) {
    return true
  }

  return isEditable(date)
}

function isMovableCell(row, c) {

  // 編集不可日は移動対象外
  if (!isEditableCell(c)) {
    return false
  }

  // 区別
  if (c === 0) return true

  // 品目
  if (c === 1) return true

  // 数量部分
  const idx = c - 2

  if (idx < 0) {
    return false
  }

  const inner = idx % 6

  /*
    0 ml_value
    1 ml_unit
    2 hon_value
    3 hon_unit
    4 hako_value
    5 hako_unit
  */

  // 野菜・果物・お米
  if (
    ["野菜", "果物", "お米"].includes(row.kubetsu)
  ) {
    // ml と hako を飛ばす
    if ([0,1,4,5].includes(inner)) {
      return false
    }
  }

  return true
}

onMounted(async () => {
  const sname = localStorage.getItem("pref")
  /*const year = new Date().getFullYear()*/
  const year = 2025

  const res = await axios.get(
    "/api/osonae",
    {
      params: { sname, year }
    }
  )

  setRowsFromDB(res.data)
})

window.addEventListener("click", () => {
  menu.value.visible = false
})

</script>

<style scoped>
.grid {
  padding: 20px;
  display: flex;
  justify-content: center;
}

.table-wrap {
  overflow-x: scroll;
  overflow-y: auto;

  max-width: 100%;
  max-height: calc(100vh - 80px);
}

table {
  border-collapse: collapse;
  table-layout: fixed;
  width: max-content;
  display: inline-table;
}

/* ===== 共通 ===== */

th,
td {
  border: 1px solid #ccc;
  text-align: center;
  overflow: visible;
  white-space: nowrap;
  padding: 0;
}

th {
  background: #eee;
}

/* ===== colgroup固定 ===== */

.col-no {
  width: 40px;
}

.col-kubetsu {
  width: 80px;
}

.col-hinmoku {
  width: 240px;
}

.col-qty {
  width: 220px;
}

/* ===== header固定 ===== */

.no-header,
.no-cell {
  width: 40px;
  min-width: 40px;
  max-width: 40px;
}

.kubetsu-header,
.kubetsu-cell {
  width: 80px;
  min-width: 80px;
  max-width: 80px;
}

.hinmoku-header,
.hinmoku-cell {
  width: 240px;
  min-width: 240px;
  max-width: 240px;
}

.qty-cell {
  width: 220px;
  min-width: 220px;
  max-width: 220px;
  padding: 0;
}

/* ===== select ===== */

input,
select {
  background-color: white;
}

/* 必須 */
input.requiredCell,
select.requiredCell {
  background-color: #f8fc03 !important;
}

/* select */
select {
  width: 100%;
  display: block;

  border: none;
  margin: 0;
  padding: 4px;

  box-sizing: border-box;

  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}

.kubetsu-select {
  width: 70px;
  min-width: 70px;
  max-width: 70px;
}

/* ===== 品目 ===== */

.hinmoku-input {
  width: 230px;
  min-width: 230px;
  max-width: 230px;

  text-align: left;
  box-sizing: border-box;
}

/* ===== 数量レイアウト ===== */

.row-top,
.row-bottom {
  display: flex;
  align-items: center;
  justify-content: center;
}

.row-top {
  border-bottom: 1px dashed #ccc;
}

/* ===== input ===== */

input {
  width: 50px;
  border: none;
  padding: 4px;
  text-align: right;
  box-sizing: border-box;
}

/* ===== unit ===== */

.unit {
  width: 55px;
  min-width: 55px;
  max-width: 55px;

  /*height: 100%;*/

  border: none;
  font-size: 12px;

}
/* ===== focus ===== */

input:focus,
select:focus {
  outline: none;
  box-shadow: inset 0 0 0 2px #4cafef;
}

.context-menu {
  position: fixed;
  background: white;
  border: 1px solid #ccc;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  z-index: 9999;
  min-width: 120px;
}

.menu-item {
  padding: 10px 14px;
  cursor: pointer;
}

.menu-item:hover {
  background: #f0f4ff;
}

.menu-item.danger:hover {
  background: #ffe5e5;
  color: #c00;
}

.no-cell {
  cursor: grab;
}

.no-cell:active {
  cursor: grabbing;
}

.sortable-chosen td {
  background: #fce2e2;
}

.sortable-drag td {
  background: #8cffd3;
}

input:disabled,
select:disabled {
  /*background: #f3f3f3;*/
  color: #999;
  cursor: not-allowed;
}

</style>
