<template>
  <div class="grid">
    <div class="content-area">
      <div class="header-area">
        <div class="title-area">
          <h1 class="report-title">感謝祭お供え報告書</h1>      
          <div class="report-subtitle">
            所属【　{{ sname }}　】　{{ mode === "ALL" ? "全日分編集" : mode + "日締切分編集" }}
          </div>
        </div>
        <button class="print-btn" @click="printTable">印刷</button>
      </div>

      <div class="save-info">
        最終更新 {{ lastSavedAt }}
      </div>
      <div class="report-subtitle rice-title">
        ◆野菜、果物、特産品
      </div>
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
            item-key="autono"
            tag="tbody"
            handle=".no-cell"
            @end="handleDragEnd"
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
                  @blur="saveRow(row)"
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
                  @blur="saveRow(row)"
                />
              </td>

              <!-- 数量 -->
              <template v-for="(q, qi) in quantityColumns" :key="q.date">
                <td class="qty-cell">
                  <!-- 上段 -->
                  <div class="row-top">
                    <!-- ml -->
                    <input type="number"
                      min="0"
                      @input="e => {
                        if (e.target.value < 0) {
                          e.target.value = 0
                          row.quantities[q.date].ml_value = 0
                        }
                      }"
                      v-model="row.quantities[q.date].ml_value"
                      :disabled="!isEditable(q.date)"
                      :class="{ requiredCell: isRequired(row, 'ml_value') && isEditable(q.date) }"
                      @keydown="handleKey($event, r, getColIndex(qi, 0))"
                      :ref="el => setRef(el, r, getColIndex(qi, 0))"
                      @blur="saveRow(row)"
                    />
                    <select
                      v-model="row.quantities[q.date].ml_unit"
                      :disabled="!isEditable(q.date)"
                      class="unit"
                      :class="{ requiredCell: isRequired(row, 'ml_unit') && isEditable(q.date) }"
                      @keydown="handleKey($event, r, getColIndex(qi, 0, true))"
                      :ref="el => setRef(el, r, getColIndex(qi, 0, true))"
                      @blur="saveRow(row)"
                    >
                      <option v-for="u in unitOptions1" :key="u">{{ u }}</option>
                    </select>
                    
                    <!-- 本 -->
                    <input type="number"
                      min="0"
                      @input="e => {
                        if (e.target.value < 0) {
                          e.target.value = 0
                          row.quantities[q.date].hon_value = 0
                        }
                      }"
                      v-model="row.quantities[q.date].hon_value"
                      :disabled="!isEditable(q.date)"
                      :class="{ requiredCell: isRequired(row, 'hon_value') && isEditable(q.date) }"
                      @keydown="handleKey($event, r, getColIndex(qi, 1))"
                      :ref="el => setRef(el, r, getColIndex(qi, 1))"
                      @blur="saveRow(row)"
                    />
                    <select
                      v-model="row.quantities[q.date].hon_unit"
                      :disabled="!isEditable(q.date)"
                      class="unit"
                      :class="{ requiredCell: isRequired(row, 'hon_unit') && isEditable(q.date) }"
                      @keydown="handleKey($event, r, getColIndex(qi, 1, true))"
                      :ref="el => setRef(el, r, getColIndex(qi, 1, true))"
                      @blur="saveRow(row)"
                    >
                      <option v-for="u in unitOptions2" :key="u">{{ u }}</option>
                    </select>
                  </div>

                  <!-- 下段 -->
                  <div class="row-bottom">
                    <!-- 変更なし -->
                    <button
                      v-if="q.date !== '20日'"
                      class="copy-btn"
                      :disabled="!isEditable(q.date)"
                      @click="copyPreviousDay(row, q.date)"
                    >
                      変更なし
                    </button>
                    <!-- 箱 -->
                    <input type="number"
                      min="0"
                      @input="e => {
                        if (e.target.value < 0) {
                          e.target.value = 0
                          row.quantities[q.date].hako_value = 0
                        }
                      }"                      
                      v-model="row.quantities[q.date].hako_value"
                      :disabled="!isEditable(q.date)"
                      :class="{ requiredCell: isRequired(row, 'hako_value') && isEditable(q.date) }"
                      @keydown="handleKey($event, r, getColIndex(qi, 2))"
                      :ref="el => setRef(el, r, getColIndex(qi, 2))"
                      @blur="saveRow(row)"
                    />
                    <select
                      v-model="row.quantities[q.date].hako_unit"
                      :disabled="!isEditable(q.date)"
                      class="unit"
                      :class="{ requiredCell: isRequired(row, 'hako_unit') && isEditable(q.date) }"
                      @keydown="handleKey($event, r, getColIndex(qi, 2, true))"
                      :ref="el => setRef(el, r, getColIndex(qi, 2, true))"
                      @blur="saveRow(row)"
                    >
                      <option v-for="u in unitOptions3" :key="u">{{ u }}</option>
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
  </div>
</template>

<script setup>
import axios from "axios"
import { onMounted } from "vue"
import { ref, nextTick } from "vue"
import { useRoute, useRouter } from "vue-router"
/*import { useRoute } from "vue-router"*/
import draggable from "vuedraggable"

const editTable = ref([])

const route = useRoute()
const router = useRouter()

const sname = route.query.sname
const mode = route.query.mode
/*const shozokuid = route.query.shozokuid*/
const shozokuid = localStorage.getItem("shozokuid")
const regaiflg = Number(localStorage.getItem("regaiflg") || 0)

// 最終保存日時
const lastSavedAt = ref(localStorage.getItem("lastSavedAt") || "")

/* ===== 定義 ===== */
const quantityColumns = [
  { date: "20日" },
  { date: "25日" },
  { date: "28日" },
]

const kubetsuOptions = ["野菜", "果物", "特産", "お米"]
const unitOptions1 = ["g", "kg", "ml", "L", "本","個", "袋", "箱", "g入", "kg入", "ml入", "L入", "本入","個入", "袋入", "箱入"]
const unitOptions2 = ["g", "kg", "ml", "L", "本","個", "袋", "箱"]
const unitOptions3 = ["箱", "袋","個"]

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

async function insertRow(index) {
  rows.value.splice(index, 0, createRow(index + 1))

  renumberRows()

  for (const row of rows.value) {
    await saveRow(row)
  }

  menu.value.visible = false
}
/*
async function insertRow(index) {

  menu.value.visible = false

  // 後ろから no 更新
  for (let i = rows.value.length - 1; i >= index; i--) {

    rows.value[i].no = rows.value[i].no + 1

    await saveRow(rows.value[i])
  }

  // 新規行追加
  const newRow = createRow(index + 1)

  rows.value.splice(index, 0, newRow)

  renumberRows()

  // 新規保存
  await saveRow(newRow)
}
*/

async function confirmDelete(index) {

  menu.value.visible = false

  if (!confirm((index + 1) + "行目を削除しますか？")) {
    return
  }

  //const deletedNo = rows.value[index].no
  const autono = rows.value[index].autono

  rows.value.splice(index, 1)

  //await deleteRowDB(deletedNo)
  await deleteRowDB(autono)

  renumberRows()

  for (const row of rows.value) {
    await saveRow(row)
  }
}

function renumberRows() {
  rows.value.forEach((row, i) => {
    row.no = i + 1
  })
}
async function deleteRowDB(autono) {

  await axios.post(
    "/api/osonae/delete",
    {
      autono
      //shozokuid,
      //year: 2025,
      //no
    }
  )
}

/* ===== 行生成 ===== */
function createRow(no) {
  const quantities = {}

  quantityColumns.forEach(q => {
    quantities[q.date] = {
      ml_value: "",
      ml_unit: "",
      hon_value: "",
      hon_unit: "",
      hako_value: "",
      hako_unit: "",
    }
  })

  return {
    autono: null,
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

  /*if (e.key === "Enter") {
    e.preventDefault()
    e.shiftKey ? movePrev(r, c) : moveNext(r, c)
  }*/
  if (e.key === "Enter") {

    const value = e.target.value

    const idx = c - 2

    let isNumberColumn = false

    if (idx >= 0) {

      const inner = idx % 6

      isNumberColumn = [0, 2, 4].includes(inner)
    }

    if (isNumberColumn && !validateCell(value)) {

      alert("数字を入力してください")

      nextTick(() => {
        e.target.focus()
        e.target.select()
      })

      return
    }

    e.preventDefault()

    e.shiftKey
      ? movePrev(r, c)
      : moveNext(r, c)
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
      autono: d.autono,
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

function normalize(d) {
  return new Date(d)
}

function isEditable(date) {
  //if (mode === "ALL") {
  //  return true
  //}
  //return mode === date.replace("日", "")

  if (editTable.value.length < 3) {
    return false
  }

  const today = new Date()

  const d1 = new normalize(editTable.value[0].editdt)
  const d2 = new normalize(editTable.value[1].editdt)
  const d3 = new normalize(editTable.value[2].editdt)
  const d4 = new normalize(editTable.value[3].editdt)
  const d5 = new normalize(editTable.value[4].editdt)
  const d6 = new normalize(editTable.value[5].editdt)
  const d7 = new normalize(editTable.value[6].editdt)

  if (date === "20日") {
    return today > d1 && today <= d2
  }

  if (date === "25日") {

    const endDate = regaiflg === 1 ? d7 : d4

    return today > d3 && today <= endDate
  }

  if (date === "28日") {
    return today > d5 && today <= d6
  }

  return false

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
  //if (c === 0) return true
  if (c === 0) return !row.kubetsu
  // 品目
  //if (c === 1) return true
  if (c === 1) return !row.hinmoku

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

function copyPreviousDay(row, targetDate) {

  let sourceDate = ""

  if (targetDate === "25日") {
    sourceDate = "20日"
  }

  if (targetDate === "28日") {
    sourceDate = "25日"
  }

  if (!sourceDate) {
    return
  }

  row.quantities[targetDate] = {
    ...row.quantities[sourceDate]
  }

  saveRow(row)
}

function isNumber(val) {

  if (val === "" || val === null) {
    return true
  }

  return !isNaN(val)
}
function validateCell(value) {

  return isNumber(value)
}

async function saveRow(row) {

  // 未入力行は保存しない
  if (!row.kubetsu || !row.hinmoku) {
    return
  }

  try {

    const res = await axios.post(
      "/api/osonae/save",
      {
        autono: row.autono,

        shozokuid,
        year: 2025,

        no: row.no,
        kubetsu: row.kubetsu,
        hinmoku: row.hinmoku,

        quantities: row.quantities
      }
    )
    // INSERT後に autono を保持
    row.autono = res.data.autono

    lastSavedAt.value = new Date().toLocaleString("ja-JP")
    localStorage.setItem("lastSavedAt",lastSavedAt.value)

  } catch (e) {

    console.log(e.response?.data)
    console.log(row)

    alert("保存失敗")
  }
}

async function saveAllRows() {

  await axios.post(
    "/api/osonae/replaceAll",
    {
      shozokuid,
      year: 2025,
      rows: rows.value
    }
  )
}

function printTable() {

  localStorage.setItem(
    "printRows",
    JSON.stringify(rows.value)
  )

  //router.push("/print")
  const url = router.resolve({
    path: "/print"
  }).href

  window.open(url, "_blank")

}

async function handleDragEnd() {

  renumberRows()

  for (const row of rows.value) {
    await saveRow(row)
  }
 
}

onMounted(async () => {

  console.log("onMounted start")

  const resEdit = await axios.get("/api/editdate")
  console.log(resEdit.data)
  editTable.value = resEdit.data

  const sname = localStorage.getItem("pref")
  /*const year = new Date().getFullYear()*/
  const year = 2025

  const res = await axios.get(
    "/api/osonae",
    {
      params: { sname, year }
    }
  )

  console.log(res.data)

  setRowsFromDB(res.data)
  //追加0608
  if (res.data.length > 0) {
    lastSavedAt.value = res.data[0].updatedt
  }

  // 初期カーソル
  nextTick(() => {
    focusCell(0, 0)
  })
  
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
  border-collapse: separate;
  border-spacing: 0;

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

/* ===== sticky header ===== */

thead th {
  position: sticky;
  background: #eee;
}

thead tr:first-child th {
  top: 0;
  z-index: 20;
  height: 40px;
}

thead tr:nth-child(2) th {
  top: 40px;
  z-index: 19;
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
  width: 235px;
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
  /*width: 240px;
  min-width: 240px;
  max-width: 240px;*/
  padding: 0;
}

/* ===== select ===== */

input,
select {
  background-color: white;
  font-size: 18px;
}

/* 必須 */
input.requiredCell,
select.requiredCell {
  background-color: #fcfdbf !important;
}

/* select */
select {
  width: 100%;
  display: block;

  border: none;
  margin: 0;
  padding: 8px;

  box-sizing: border-box;

  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}

.kubetsu-select {
  width: 78px;
  min-width: 78px;
  max-width: 78px;
  text-align: center;
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

.row-top {
  display: flex;
  align-items: center;
  justify-content: center;
  border-bottom: 1px dashed #ccc;
}

.row-bottom {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative; /* 見た目は通常配置のまま、absolute(.copy-btn内) の基準点になる */
}

/* ===== input ===== */

input {
  width: 60px;
  border: none;
  padding: 5px;
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

.content-area {
  display: flex;
  flex-direction: column;
  align-items: stretch;
}

.report-title {
  text-align: center;
  font-size: 32px;
  font-weight: bold;
  letter-spacing: 4px;
  margin-bottom: 16px;
  color: #333;
}

.report-subtitle {
  width: 100%;
  text-align: left;
  font-size: 25px;
  font-weight: bold;
  color: #020080;
  margin-bottom: 18px;
  letter-spacing: 1px;
}

.copy-btn {
  position: absolute; /* 通常の配置ルールから外して、好きな位置へ置く */
  left: 2px;
  font-size: 11px;
  padding: 2px 6px;
  border: 1px solid #bbb;
  background: #f5f5f5;
  cursor: pointer;
  white-space: nowrap;
  /*margin-right: 21px;*/
}

.copy-btn:hover:not(:disabled) {
  background: #e6f0ff;
}

.copy-btn:disabled {
  color: #999;
  cursor: not-allowed;
}

/*
.print-btn {
  padding: 4px 12px;

  background: #f5f5f5;
  color: #000;

  border: 1px solid #bbb;
  border-radius: 4px;

  font-size: 13px;
  font-weight: normal;

  cursor: pointer;

  box-shadow: none;
}
*/
.print-btn {
  padding: 8px 20px;

  background: #f5f5f5;
  border: 1px solid #bbb;
  border-radius: 4px;

  font-size: 16px;
  font-weight: bold;

  cursor: pointer;
}

.print-btn:hover {
  background: #eaeaea;
}

.print-btn:active {
  transform: translateY(0);
}

.header-area {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.title-area {
  flex: 1;
}

.save-info {
  text-align: right;
  font-size: 11px;
  color: #777;
  margin-bottom: 2px;
}

</style>
