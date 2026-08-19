<template>
  <div class="grid">
    <div class="content-area">

      <!-- ヘッダー -->
      <div class="header-area">

        <button class="back-btn" @click="goBack">
          戻る
        </button>

        <div class="title-area">
          <h1 class="report-title">
            感謝祭お供え報告書
          </h1>

          <div class="report-subtitle">
            所属【　{{ sname }}　】
          </div>
        </div>

        <button class="print-btn" @click="printTable">
          印刷
        </button>

      </div>


      <!-- 最終更新 -->
      <div class="save-info">
        最終更新 {{ lastSavedAt }}
      </div>


      <div class="report-subtitle">
        ◆お米、野菜、果物、特産品
      </div>


      <div class="table-wrap">

        <table>

          <colgroup>

            <col class="col-no">
            <col class="col-kubetsu">
            <col class="col-hinmoku">
            <col class="col-chugokugo">
            <col class="col-area">
            <col class="col-jissisyear">
            <col class="col-seisansha">
            <col class="col-shinjkab">
            <col class="col-suryo">

          </colgroup>


          <thead>

            <!-- 1段目 -->
            <tr>

              <th rowspan="2" class="no-header">
                No
              </th>

              <th rowspan="2" class="kubetsu-header">
                区別
              </th>

              <th rowspan="2" class="hinmoku-header">
                品　目
              </th>

              <th rowspan="2" class="chugokugo-header">
                中国語
              </th>

              <th rowspan="2" class="area-header">
                地域名
              </th>

              <th rowspan="2" class="jissisyear-header">
                自然農法<br>
                実施年数
              </th>

              <th rowspan="2" class="seisansha-header">
                生産者名
              </th>

              <th rowspan="2" class="shinjkab-header">
                信者<br>
                未信者
              </th>

              <th class="quantity-header">
                数量
              </th>

            </tr>


            <!-- 2段目 -->
            <tr>

              <th class="suryo-header">

                野菜(kg)、果物(kg×箱数)、特産(個数×箱数)<br>

                <span>
                  20日　締切
                </span>

                <div class="correction-text">
                  変更後はすぐに訂正報告
                </div>

              </th>

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

                <!-- No -->
                <td
                  class="no-cell"
                  @contextmenu.prevent="
                    openContextMenu($event, r)
                  "
                >
                  {{ row.no }}
                </td>


                <!-- 区別 -->
                <td class="kubetsu-cell">

                  <select
                    class="kubetsu-select"
                    v-model="row.kubetsu"
                    @keydown="
                      handleKey($event, r, 0)
                    "
                    :ref="
                      el => setRef(el, r, 0)
                    "
                    @blur="saveRow(row)"
                  >

                    <option value=""></option>

                    <option
                      v-for="opt in kubetsuOptions"
                      :key="opt"
                      :value="opt"
                    >
                      {{ opt }}
                    </option>

                  </select>

                </td>


                <!-- 品目 -->
                <td class="hinmoku-cell">

                  <input
                    class="hinmoku-input"
                    v-model="row.hinmoku"
                    @keydown="
                      handleKey($event, r, 1)
                    "
                    :ref="
                      el => setRef(el, r, 1)
                    "
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- 中国語 -->
                <td class="chugokugo-cell">

                  <input
                    class="chugokugo-input"
                    v-model="row.chugokugo"
                    @keydown="
                      handleKey($event, r, 2)
                    "
                    :ref="
                      el => setRef(el, r, 2)
                    "
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- 地域名 -->
                <td class="area-cell">

                  <input
                    class="area-input"
                    v-model="row.chiikimei"
                    @keydown="
                      handleKey($event, r, 3)
                    "
                    :ref="
                      el => setRef(el, r, 3)
                    "
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- 自然農法実施年数 -->
                <td class="jissisyear-cell">

                  <input
                    class="jissisyear-input"
                    v-model="row.jissisyear"
                    @keydown="
                      handleKey($event, r, 4)
                    "
                    :ref="
                      el => setRef(el, r, 4)
                    "
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- 生産者名 -->
                <td class="seisansha-cell">

                  <input
                    class="seisansha-input"
                    v-model="row.seisansha"
                    @keydown="
                      handleKey($event, r, 5)
                    "
                    :ref="
                      el => setRef(el, r, 5)
                    "
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- 信者/未信者 -->
                <td class="shinjkab-cell">

                  <select
                    class="shinjkab-select"
                    v-model="row.shinjkab"
                    @keydown="
                      handleKey($event, r, 6)
                    "
                    :ref="
                      el => setRef(el, r, 6)
                    "
                    @blur="saveRow(row)"
                  >

                    <option value=""></option>

                    <option value="信者">
                      信者
                    </option>

                    <option value="未信者">
                      未信者
                    </option>

                  </select>

                </td>


                <!-- 数量 -->
                <td class="suryo-cell">

                  <input
                    class="suryo-input"
                    v-model="row.suryo"
                    @keydown="
                      handleKey($event, r, 7)
                    "
                    :ref="
                      el => setRef(el, r, 7)
                    "
                    @blur="saveRow(row)"
                  >

                </td>

              </tr>

            </template>

          </draggable>

        </table>


        <!-- 右クリックメニュー -->
        <div
          v-if="menu.visible"
          class="context-menu"
          :style="{
            top: menu.y + 'px',
            left: menu.x + 'px'
          }"
        >

          <div
            class="menu-item"
            @click="insertRow(menu.rowIndex)"
          >
            行挿入
          </div>

          <div
            class="menu-item danger"
            @click="
              confirmDelete(menu.rowIndex)
            "
          >
            行削除
          </div>

        </div>

      </div>

    </div>
  </div>
</template>


<script setup>

import axios from "axios"

import {
  ref,
  nextTick,
  onMounted
} from "vue"

import {
  useRoute,
  useRouter
} from "vue-router"

import draggable from "vuedraggable"


/* =========================
   Router
========================= */

const route = useRoute()
const router = useRouter()


/* =========================
   基本情報
========================= */

const sname =
  route.query.sname ||
  localStorage.getItem("pref")

const shozokuid =
  localStorage.getItem("shozokuid")

const year = 2025


/* =========================
   最終更新
========================= */

const lastSavedAt = ref(
  localStorage.getItem(
    "kaigaiLastSavedAt"
  ) || ""
)


/* =========================
   区別
========================= */

const kubetsuOptions = [
  "お米",
  "野菜",
  "果物",
  "特産"
]


/* =========================
   右クリックメニュー
========================= */

const menu = ref({
  visible: false,
  x: 0,
  y: 0,
  rowIndex: null
})


function openContextMenu(
  e,
  rowIndex
) {

  menu.value.visible = true

  menu.value.x = e.clientX
  menu.value.y = e.clientY

  menu.value.rowIndex = rowIndex

}


/* =========================
   行生成
========================= */

function createRow(no) {

  return {

    autono: null,

    no,

    excelno: null,

    kubetsu: "",

    hinmoku: "",

    chugokugo: "",

    chiikimei: "",

    jissisyear: "",

    seisansha: "",

    shinjkab: "",

    suryo: ""

  }

}


const rows = ref([
  createRow(1)
])


/* =========================
   行番号
========================= */

function renumberRows() {

  rows.value.forEach(
    (row, index) => {

      row.no = index + 1

    }
  )

}


/* =========================
   行挿入
========================= */

async function insertRow(index) {

  menu.value.visible = false

  rows.value.splice(
    index,
    0,
    createRow(index + 1)
  )

  renumberRows()


  for (
    const row of rows.value
  ) {

    await saveRow(row)

  }

}


/* =========================
   行削除
========================= */

async function confirmDelete(index) {

  menu.value.visible = false


  if (
    !confirm(
      `${index + 1}行目を削除しますか？`
    )
  ) {

    return

  }


  const autono =
    rows.value[index].autono


  rows.value.splice(
    index,
    1
  )


  if (autono) {

    await deleteRowDB(
      autono
    )

  }


  renumberRows()


  for (
    const row of rows.value
  ) {

    await saveRow(row)

  }

}


async function deleteRowDB(
  autono
) {

  await axios.post(
    "/api/kaigai/delete",
    {
      autono
    }
  )

}


/* =========================
   セル参照
========================= */

const cellRefs = ref([])


function setRef(
  el,
  r,
  c
) {

  if (!cellRefs.value[r]) {

    cellRefs.value[r] = []

  }

  cellRefs.value[r][c] = el

}


function focusCell(
  r,
  c
) {

  nextTick(() => {

    cellRefs
      .value[r]?.[c]
      ?.focus()

  })

}


/* =========================
   列数
========================= */

const totalCols = 8


/* =========================
   次へ
========================= */

function moveNext(
  r,
  c
) {

  let nr = r
  let nc = c + 1


  if (
    nc >= totalCols
  ) {

    nc = 0
    nr++

  }


  if (
    !rows.value[nr]
  ) {

    rows.value.push(
      createRow(
        rows.value.length + 1
      )
    )

  }


  focusCell(
    nr,
    nc
  )

}


/* =========================
   前へ
========================= */

function movePrev(
  r,
  c
) {

  let nr = r
  let nc = c - 1


  if (
    nc < 0
  ) {

    nr--

    if (nr < 0) {

      return

    }

    nc =
      totalCols - 1

  }


  focusCell(
    nr,
    nc
  )

}


/* =========================
   上下
========================= */

function moveVertical(
  r,
  c,
  dir
) {

  const nr =
    r + dir


  if (
    nr < 0
  ) {

    return

  }


  if (
    !rows.value[nr]
  ) {

    rows.value.push(
      createRow(
        rows.value.length + 1
      )
    )

  }


  focusCell(
    nr,
    c
  )

}


/* =========================
   キー操作
========================= */

function handleKey(
  e,
  r,
  c
) {

  const tag =
    e.target.tagName
      ?.toLowerCase()

  const isSelect =
    tag === "select"


  if (
    e.key === "Enter"
  ) {

    e.preventDefault()

    if (
      e.shiftKey
    ) {

      movePrev(r, c)

    } else {

      moveNext(r, c)

    }

    return

  }


  if (
    e.key === "Tab"
  ) {

    e.preventDefault()

    if (
      e.shiftKey
    ) {

      movePrev(r, c)

    } else {

      moveNext(r, c)

    }

    return

  }


  if (
    e.key === "ArrowRight" &&
    !isSelect
  ) {

    e.preventDefault()

    moveNext(r, c)

  }


  if (
    e.key === "ArrowLeft" &&
    !isSelect
  ) {

    e.preventDefault()

    movePrev(r, c)

  }


  if (
    e.key === "ArrowDown" &&
    !isSelect
  ) {

    e.preventDefault()

    moveVertical(
      r,
      c,
      1
    )

  }


  if (
    e.key === "ArrowUp" &&
    !isSelect
  ) {

    e.preventDefault()

    moveVertical(
      r,
      c,
      -1
    )

  }

}


/* =========================
   DB → Vue
========================= */

function setRowsFromDB(
  data
) {

  rows.value =
    data.map(
      d => ({

        autono:
          d.autono,

        no:
          d.no,

        excelno:
          d.excelno,

        kubetsu:
          d.kubetsu ?? "",

        hinmoku:
          d.hinmoku ?? "",

        chugokugo:
          d.chugokugo ?? "",

        chiikimei:
          d.chiikimei ?? "",

        jissisyear:
          d.jissisyear ?? "",

        seisansha:
          d.seisansha ?? "",

        shinjkab:
          d.shinjkab ?? "",

        suryo:
          d.suryo ?? ""

      })
    )


  /*
   * DBにデータがなければ
   * 新規1行
   */

  if (
    rows.value.length === 0
  ) {

    rows.value = [
      createRow(1)
    ]

  }

}


/* =========================
   保存
========================= */

async function saveRow(
  row
) {

  /*
   * 未入力行は保存しない
   */

  if (
    !row.kubetsu ||
    !row.hinmoku
  ) {

    return

  }


  try {

    const res =
      await axios.post(
        "/api/kaigai/save",
        {

          autono:
            row.autono,

          shozokuid,

          year,

          no:
            row.no,

          excelno:
            row.excelno,

          kubetsu:
            row.kubetsu,

          hinmoku:
            row.hinmoku,

          chugokugo:
            row.chugokugo,

          chiikimei:
            row.chiikimei,

          jissisyear:
            row.jissisyear,

          seisansha:
            row.seisansha,

          shinjkab:
            row.shinjkab,

          suryo:
            row.suryo

        }
      )


    /*
     * INSERT後
     * autonoを保持
     */

    row.autono =
      res.data.autono


    lastSavedAt.value =
      new Date()
        .toLocaleString(
          "ja-JP"
        )


    localStorage.setItem(
      "kaigaiLastSavedAt",
      lastSavedAt.value
    )


  } catch (e) {

    console.error(
      e.response?.data
    )

    alert(
      "保存失敗"
    )

  }

}


/* =========================
   ドラッグ終了
========================= */

async function handleDragEnd() {

  renumberRows()


  for (
    const row of rows.value
  ) {

    await saveRow(row)

  }

}


/* =========================
   印刷
========================= */

function printTable() {

  localStorage.setItem(
    "kaigaiPrintRows",
    JSON.stringify(
      rows.value
    )
  )


  const url =
    router.resolve({
      path: "/kaigai-print"
    }).href


  window.open(
    url,
    "_blank"
  )

}


/* =========================
   戻る
========================= */

function goBack() {

  router.back()

}


/* =========================
   初期処理
========================= */

onMounted(
  async () => {

    try {

      const res =
        await axios.get(
          "/api/kaigai",
          {
            params: {
              sname,
              shozokuid,
              year
            }
          }
        )


      console.log(
        res.data
      )


      setRowsFromDB(
        res.data
      )


      if (
        res.data.length > 0
      ) {

        lastSavedAt.value =
          res.data[0].updatedt ??
          ""

      }


      nextTick(
        () => {

          focusCell(
            0,
            0
          )

        }
      )


    } catch (e) {

      console.error(e)

      alert(
        "データ取得に失敗しました"
      )

    }

  }
)


/* =========================
   メニューを閉じる
========================= */

window.addEventListener(
  "click",
  () => {

    menu.value.visible =
      false

  }
)

</script>


<style scoped>

.grid {
  padding: 20px;
  display: flex;
  justify-content: center;
}

.content-area {
  display: flex;
  flex-direction: column;
  align-items: stretch;
}


/* =========================
   ヘッダー
========================= */

.header-area {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.title-area {
  flex: 1;
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

.save-info {
  text-align: right;
  font-size: 11px;
  color: #777;
  margin-bottom: 2px;
}


/* =========================
   テーブル
========================= */

.table-wrap {
  overflow-x: auto;
  overflow-y: auto;

  max-width: 100%;
  max-height:
    calc(100vh - 80px);
}

table {
  border-collapse: separate;
  border-spacing: 0;

  table-layout: fixed;

  width: max-content;

  display: inline-table;
}

th,
td {
  border: 1px solid #999;

  text-align: center;

  overflow: visible;

  white-space: nowrap;

  padding: 0;
}


/* =========================
   ヘッダー固定
========================= */

thead th {
  position: sticky;
  background: #fff;
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


/* =========================
   列幅
========================= */

.col-no {
  width: 40px;
}

.col-kubetsu {
  width: 45px;
}

.col-hinmoku {
  width: 210px;
}

.col-chugokugo {
  width: 120px;
}

.col-area {
  width: 80px;
}

.col-jissisyear {
  width: 100px;
}

.col-seisansha {
  width: 110px;
}

.col-shinjkab {
  width: 70px;
}

.col-suryo {
  width: 235px;
}


/* =========================
   固定幅
========================= */

.no-header,
.no-cell {
  width: 40px;
  min-width: 40px;
  max-width: 40px;
}

.kubetsu-header,
.kubetsu-cell {
  width: 45px;
  min-width: 45px;
  max-width: 45px;
}

.hinmoku-header,
.hinmoku-cell {
  width: 210px;
  min-width: 210px;
  max-width: 210px;
}

.chugokugo-header,
.chugokugo-cell {
  width: 120px;
  min-width: 120px;
  max-width: 120px;
}

.area-header,
.area-cell {
  width: 80px;
  min-width: 80px;
  max-width: 80px;
}

.jissisyear-header,
.jissisyear-cell {
  width: 100px;
  min-width: 100px;
  max-width: 100px;
}

.seisansha-header,
.seisansha-cell {
  width: 110px;
  min-width: 110px;
  max-width: 110px;
}

.shinjkab-header,
.shinjkab-cell {
  width: 70px;
  min-width: 70px;
  max-width: 70px;
}

.suryo-cell {
  width: 235px;
  min-width: 235px;
}


/* =========================
   入力
========================= */

input,
select {
  background-color: white;
  font-size: 15px;
}

input {
  border: none;
  padding: 5px;
  box-sizing: border-box;
}

select {
  border: none;
  padding: 7px;

  box-sizing: border-box;

  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}


/* =========================
   各入力
========================= */

.kubetsu-select {
  width: 43px;
  text-align: center;
}

.hinmoku-input {
  width: 200px;
  text-align: left;
}

.chugokugo-input {
  width: 115px;
  text-align: left;
}

.area-input {
  width: 75px;
  text-align: center;
}

.jissisyear-input {
  width: 95px;
  text-align: center;
}

.seisansha-input {
  width: 105px;
  text-align: left;
}

.shinjkab-select {
  width: 68px;
  text-align: center;
}

.suryo-input {
  width: 230px;
  text-align: center;
  font-size: 16px;
}


/* =========================
   数量ヘッダー
========================= */

.quantity-header {
  font-size: 18px;
}

.suryo-header {
  font-size: 13px;
  line-height: 1.5;
}

.suryo-header span {
  font-size: 16px;
}

.correction-text {
  color: red;
  font-size: 13px;
}


/* =========================
   フォーカス
========================= */

input:focus,
select:focus {
  outline: none;

  box-shadow:
    inset 0 0 0 2px #4cafef;
}


/* =========================
   右クリックメニュー
========================= */

.context-menu {
  position: fixed;

  background: white;

  border: 1px solid #ccc;

  box-shadow:
    0 4px 10px
    rgba(0,0,0,0.15);

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


/* =========================
   ドラッグ
========================= */

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


/* =========================
   ボタン
========================= */

.print-btn,
.back-btn {
  padding: 8px 20px;

  background: #f5f5f5;

  border: 1px solid #bbb;

  border-radius: 4px;

  font-size: 16px;

  font-weight: bold;

  cursor: pointer;
}

.print-btn:hover,
.back-btn:hover {
  background: #eaeaea;
}

</style>