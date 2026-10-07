<template>
  <div class="grid">
    <div class="content-area">

      <!-- ヘッダー -->
      <div class="header-area">

        <button
          class="back-btn"
          @click="goBack"
        >
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

        <button
          class="print-btn"
          @click="printTable"
        >
          印刷
        </button>

      </div>


      <!-- 最終更新 -->
      <div class="save-info">
        最終更新 {{ lastSavedAt }}<br>
        入力者：{{ lastSavedTantoshaname }}
      </div>


      <div class="report-subtitle">
        ◆お米、野菜、果物、特産品
      </div>


      <div class="table-wrap">

        <table>

          <!-- =========================
               列幅
          ========================= -->
          <colgroup>

            <col class="col-no">
            <col class="col-kubetsu">
            <col class="col-hinmoku">

            <!-- 香港のみ表示 -->
            <col
              v-if="isHongKong"
              class="col-chugokugo"
            >

            <col class="col-area">
            <col class="col-jissiyear">
            <col class="col-seisansha">
            <col class="col-shinjakb">
            <col class="col-suryo">

          </colgroup>


          <thead>

            <!-- 1段目 -->
            <tr>

              <th
                rowspan="2"
                class="no-header"
              >
                No
              </th>


              <th
                rowspan="2"
                class="kubetsu-header"
              >
                区別
              </th>


              <th
                rowspan="2"
                class="hinmoku-header"
              >
                品　目
              </th>


              <!-- 香港のみ -->
              <th
                v-if="isHongKong"
                rowspan="2"
                class="chugokugo-header"
              >
                中国語
              </th>


              <th
                rowspan="2"
                class="area-header"
              >
                地域名
              </th>


              <th
                rowspan="2"
                class="jissiyear-header"
              >
                自然農法<br>
                実施年数
              </th>


              <th
                rowspan="2"
                class="seisansha-header"
              >
                生産者名
              </th>


              <th
                rowspan="2"
                class="shinjakb-header"
              >
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
            item-key="_key"
            tag="tbody"
            handle=".no-cell"
            @end="handleDragEnd"
          >

            <template
              #item="{
                element: row,
                index: r
              }"
            >

              <tr>

                <!-- =========================
                     No
                ========================= -->
                <td
                  class="no-cell"
                  @contextmenu.prevent="
                    openContextMenu($event, r)
                  "
                >
                  {{ row.no }}
                </td>


                <!-- =========================
                     区別
                     表示列番号：0
                ========================= -->
                <td
                  class="kubetsu-cell"
                  :class="{
                    marked: row.mark1 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      1
                    )
                  "
                >

                  <select
                    class="kubetsu-select"
                    v-model="row.kubetsu"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('kubetsu')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('kubetsu')
                        )
                    "

                    @change="markDirty(row)"
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


                <!-- =========================
                     品目
                ========================= -->
                <td
                  class="hinmoku-cell"
                  :class="{
                    marked: row.mark2 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      2
                    )
                  "
                >

                  <input
                    class="hinmoku-input"
                    v-model="row.hinmoku"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('hinmoku')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('hinmoku')
                        )
                    "

                    @input="markDirty(row)"
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- =========================
                     中国語
                     香港のみ表示
                ========================= -->
                <td
                  v-if="isHongKong"
                  class="chugokugo-cell"
                  :class="{
                    marked: row.mark3 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      3
                    )
                  "
                >

                  <input
                    class="chugokugo-input"
                    v-model="row.chugokugo"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('chugokugo')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('chugokugo')
                        )
                    "

                    @input="markDirty(row)"
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- =========================
                     地域名
                ========================= -->
                <td
                  class="area-cell"
                  :class="{
                    marked: row.mark4 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      4
                    )
                  "
                >

                  <input
                    class="area-input"
                    v-model="row.chiikimei"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('area')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('area')
                        )
                    "

                    @input="markDirty(row)"
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- =========================
                     自然農法実施年数
                ========================= -->
                <td
                  class="jissiyear-cell"
                  :class="{
                    marked: row.mark5 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      5
                    )
                  "
                >

                  <input
                    class="jissiyear-input"
                    v-model="row.jissiyear"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('jissiyear')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('jissiyear')
                        )
                    "

                    @input="markDirty(row)"
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- =========================
                     生産者名
                ========================= -->
                <td
                  class="seisansha-cell"
                  :class="{
                    marked: row.mark6 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      6
                    )
                  "
                >

                  <input
                    class="seisansha-input"
                    v-model="row.seisansha"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('seisansha')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('seisansha')
                        )
                    "

                    @input="markDirty(row)"
                    @blur="saveRow(row)"
                  >

                </td>


                <!-- =========================
                     信者 / 未信者
                ========================= -->
                <td
                  class="shinjakb-cell"
                  :class="{
                    marked: row.mark7 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      7
                    )
                  "
                >

                  <select
                    class="shinjakb-select"
                    v-model="row.shinjakb"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('shinjakb')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('shinjakb')
                        )
                    "

                    @change="markDirty(row)"
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


                <!-- =========================
                     数量
                ========================= -->
                <td
                  class="suryo-cell"
                  :class="{
                    marked: row.mark8 === 1
                  }"
                  @contextmenu.prevent="
                    openMarkMenu(
                      $event,
                      row,
                      8
                    )
                  "
                >

                  <input
                    class="suryo-input"
                    v-model="row.suryo"

                    @keydown="
                      handleKey(
                        $event,
                        r,
                        getColumnIndex('suryo')
                      )
                    "

                    :ref="
                      el =>
                        setRef(
                          el,
                          r,
                          getColumnIndex('suryo')
                        )
                    "

                    @input="markDirty(row)"
                    @blur="saveRow(row)"
                  >

                </td>

              </tr>

            </template>

          </draggable>

        </table>


        <!-- =========================
             行操作メニュー
        ========================= -->
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


        <!-- =========================
             マーカー用メニュー
        ========================= -->
        <div
          v-if="markMenu.visible"
          class="context-menu"
          :style="{
            top: markMenu.y + 'px',
            left: markMenu.x + 'px'
          }"
        >

          <div
            class="menu-item"
            @click="toggleMarker"
          >
            マーカー
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

const tantoshaname =
  localStorage.getItem("tantoshaname") || ""

const year = ref(null)


/* =========================
   香港判定
========================= */

/*
 * 所属が「香港」の場合だけ
 * 中国語列を表示する
 */
const isHongKong =
  String(sname ?? "").trim() === "香港"


/*
 * 表示される列の一覧
 *
 * 香港：
 * 0 区別
 * 1 品目
 * 2 中国語
 * 3 地域名
 * 4 自然農法実施年数
 * 5 生産者名
 * 6 信者/未信者
 * 7 数量
 *
 * 香港以外：
 * 0 区別
 * 1 品目
 * 2 地域名
 * 3 自然農法実施年数
 * 4 生産者名
 * 5 信者/未信者
 * 6 数量
 */
const visibleColumns = isHongKong
  ? [
      "kubetsu",
      "hinmoku",
      "chugokugo",
      "area",
      "jissiyear",
      "seisansha",
      "shinjakb",
      "suryo"
    ]
  : [
      "kubetsu",
      "hinmoku",
      "area",
      "jissiyear",
      "seisansha",
      "shinjakb",
      "suryo"
    ]


/*
 * 列名から現在表示されている
 * 列番号を取得
 */
function getColumnIndex(columnName) {

  return visibleColumns.indexOf(
    columnName
  )

}


/*
 * 現在表示されている入力列数
 *
 * 香港     → 8
 * 香港以外 → 7
 */
const totalCols =
  visibleColumns.length


/* =========================
   最終更新
========================= */

const lastSavedAt =
  ref("")

const lastSavedTantoshaname =
  ref("")


// 初期表示中は保存しない
const isInitializing =
  ref(true)


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


const markMenu = ref({

  visible: false,

  x: 0,

  y: 0,

  row: null,

  markNo: null

})


function openMarkMenu(
  e,
  row,
  markNo
) {

  markMenu.value.visible =
    true

  markMenu.value.x =
    e.clientX

  markMenu.value.y =
    e.clientY

  markMenu.value.row =
    row

  markMenu.value.markNo =
    markNo

}


function openContextMenu(
  e,
  rowIndex
) {

  menu.value.visible =
    true

  menu.value.x =
    e.clientX

  menu.value.y =
    e.clientY

  menu.value.rowIndex =
    rowIndex

}


/* =========================
   行生成
========================= */

// 画面表示用の行キー
let rowKeyCounter = 0

function createRow(no) {

  return {

    // 画面表示用の固定キー
    _key: ++rowKeyCounter,

    autono: null,

    no,

    excelno: null,

    kubetsu: "",

    hinmoku: "",

    chugokugo: "",

    chiikimei: "",

    jissiyear: "",

    seisansha: "",

    shinjakb: "",

    suryo: "",

    mark1: 0,
    mark2: 0,
    mark3: 0,
    mark4: 0,
    mark5: 0,
    mark6: 0,
    mark7: 0,
    mark8: 0,

    _dirty: false

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

      row.no =
        index + 1

    }
  )

}


/* =========================
   行挿入
========================= */

async function insertRow(
  index
) {

  menu.value.visible =
    false

  rows.value.splice(
    index,
    0,
    createRow(index + 1)
  )

  renumberRows()

}

/* =========================
   行削除
========================= */

async function confirmDelete(index) {

  // 行操作メニューを閉じる
  menu.value.visible = false

  // 削除確認
  if (!confirm(`${index + 1}行目を削除しますか？`)) {
    return
  }

  // 削除対象のDB登録番号
  const autono = rows.value[index].autono

  // 画面から行を削除
  rows.value.splice(index, 1)

  // DBに登録済みの行だけ削除
  if (autono) {
    await deleteRowDB(autono)
  }

  // 全行削除されても、新規入力用の空行を1行残す
  if (rows.value.length === 0) {
    rows.value.push(createRow(1))
  }

  // Noを振り直す
  renumberRows()

  // 行番号の変更をDBへ反映
  for (const row of rows.value) {
    await saveRow(row, true)
  }

  // 先頭行の区別欄にフォーカス
  await nextTick()
  focusCell(0, 0)
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

const cellRefs =
  ref([])


function setRef(
  el,
  r,
  c
) {

  if (
    c < 0
  ) {

    return

  }


  if (
    !cellRefs.value[r]
  ) {

    cellRefs.value[r] =
      []

  }


  cellRefs.value[r][c] =
    el

}


function focusCell(
  r,
  c
) {

  nextTick(() => {

    const el =
      cellRefs
        .value[r]
        ?. [c]

    if (el) {

      el.focus()

      /*
       * inputの場合は全選択
       */
      if (
        typeof el.select ===
        "function"
      ) {

        el.select()

      }

    }

  })

}


/* =========================
   次へ
========================= */

function moveNext(
  r,
  c
) {

  let nr = r
  let nc = c + 1


  /*
   * 現在の行の最後まで行ったら
   * 次の行の先頭へ
   */
  if (
    nc >= totalCols
  ) {

    nc = 0

    nr++

  }


  /*
   * 次の行が存在しない場合
   * 新しい行を作成
   */
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


  /*
   * 行の先頭より前なら
   * 前の行の最後へ
   */
  if (
    nc < 0
  ) {

    nr--

    if (
      nr < 0
    ) {

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


  /*
   * 下の行が存在しなければ
   * 新しい行を作成
   */
  if (
    !rows.value[nr]
  ) {

    rows.value.push(
      createRow(
        rows.value.length + 1
      )
    )

  }


  /*
   * 同じ表示列番号へ移動
   *
   * 香港の場合
   * 中国語を含めて同じ列
   *
   * 香港以外の場合
   * 中国語がないので自然に飛ばされる
   */
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


  /* =========================
     Enter
  ========================= */

  if (
    e.key === "Enter"
  ) {

    e.preventDefault()


    if (
      e.shiftKey
    ) {

      movePrev(
        r,
        c
      )

    } else {

      moveNext(
        r,
        c
      )

    }

    return

  }


  /* =========================
     Tab
  ========================= */

  if (
    e.key === "Tab"
  ) {

    e.preventDefault()


    if (
      e.shiftKey
    ) {

      movePrev(
        r,
        c
      )

    } else {

      moveNext(
        r,
        c
      )

    }

    return

  }


  /*
   * selectの場合は
   * ← → ↑ ↓ をブラウザ標準動作にする
   */
  if (
    isSelect
  ) {

    return

  }


  /* =========================
     → 次のセル
  ========================= */

  if (
    e.key === "ArrowRight"
  ) {

    e.preventDefault()

    moveNext(
      r,
      c
    )

    return

  }


  /* =========================
     ← 前のセル
  ========================= */

  if (
    e.key === "ArrowLeft"
  ) {

    e.preventDefault()

    movePrev(
      r,
      c
    )

    return

  }


  /* =========================
     ↓ 下のセル
  ========================= */

  if (
    e.key === "ArrowDown"
  ) {

    e.preventDefault()

    moveVertical(
      r,
      c,
      1
    )

    return

  }


  /* =========================
     ↑ 上のセル
  ========================= */

  if (
    e.key === "ArrowUp"
  ) {

    e.preventDefault()

    moveVertical(
      r,
      c,
      -1
    )

    return

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

        _key: ++rowKeyCounter,

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

        jissiyear:
          d.jissiyear ?? "",

        seisansha:
          d.seisansha ?? "",

        shinjakb:
          d.shinjakb ?? "",

        suryo:
          d.suryo ?? "",

        mark1:
          Number(
            d.mark1 ?? 0
          ),

        mark2:
          Number(
            d.mark2 ?? 0
          ),

        mark3:
          Number(
            d.mark3 ?? 0
          ),

        mark4:
          Number(
            d.mark4 ?? 0
          ),

        mark5:
          Number(
            d.mark5 ?? 0
          ),

        mark6:
          Number(
            d.mark6 ?? 0
          ),

        mark7:
          Number(
            d.mark7 ?? 0
          ),

        mark8:
          Number(
            d.mark8 ?? 0
          ),

        _dirty: false

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
  row,
  force = false
) {

  /*
   * 初期表示中は保存しない
   */
  if (
    isInitializing.value
  ) {

    return

  }


  /*
   * 実際に変更していない場合
   * 保存しない
   */
  if (
    !force &&
    !row._dirty
  ) {

    return

  }


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

          year: year.value,

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

          jissiyear:
            row.jissiyear,

          seisansha:
            row.seisansha,

          shinjakb:
            row.shinjakb,

          suryo:
            row.suryo,

          mark1:
            row.mark1,

          mark2:
            row.mark2,

          mark3:
            row.mark3,

          mark4:
            row.mark4,

          mark5:
            row.mark5,

          mark6:
            row.mark6,

          mark7:
            row.mark7,

          mark8:
            row.mark8,

          tantoshaname

        }
      )


    /*
     * INSERT後
     * autonoを保持
     */
    row.autono =
      res.data.autono


    /*
     * DBが返した値を
     * そのまま表示
     */
    lastSavedAt.value =
      res.data.updatedt ??
      ""

    lastSavedTantoshaname.value =
      res.data.tantoshaname ??
      tantoshaname


    /*
     * 保存済みにする
     */
    row._dirty =
      false

  } catch (e) {

    console.error(
      e.response?.data
    )


    const message =
      e.response?.data?.message ||
      e.message ||
      "原因不明のエラー"


    alert(
      "保存失敗\n\n" +
      message
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

    await saveRow(
      row,
      true
    )

  }

}


/* =========================
   印刷
========================= */

function printTable() {

  localStorage.setItem(
    "printKaigaiRows",
    JSON.stringify(
      rows.value
    )
  )


  localStorage.setItem(
    "printKaigaiSname",
    sname
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
   最終更新取得
========================= */

async function loadLastSaved() {

  try {

    const res =
      await axios.get(
        "/api/kaigai/last-saved",
        {
          params: {
            shozokuid,
            year: year.value
          }
        }
      )


    lastSavedAt.value =
      res.data?.updatedt ??
      ""

    lastSavedTantoshaname.value =
      res.data?.tantoshaname ??
      ""

  } catch (e) {

    console.error(
      "最終更新情報取得失敗",
      e
    )

    lastSavedAt.value =
      ""

    lastSavedTantoshaname.value =
      ""

  }

}


/* =========================
   初期処理
========================= */

/* =========================
   初期処理
========================= */

onMounted(
  async () => {

    try {

      /*
       * 初期表示中
       */
      isInitializing.value = true


      /*
       * editdate テーブル取得
       */
      const resEdit =
        await axios.get(
          "/api/editdate"
        )

      const editTable =
        resEdit.data


      /*
       * no = 0 の editdt から年度取得
       */
      const yearRow =
        editTable.find(
          d => Number(d.no) === 0
        )


      if (
        !yearRow ||
        !yearRow.editdt
      ) {

        alert(
          "編集日付テーブルの年度を取得できません"
        )

        return

      }


      year.value =
        new Date(
          yearRow.editdt
        ).getFullYear()


      console.log(
        "取得した年度:",
        year.value
      )


      /*
       * 明細データ取得
       */
      const res =
        await axios.get(
          "/api/kaigai",
          {
            params: {
              sname,
              shozokuid,
              year: year.value
            }
          }
        )


      console.log(
        "Kaigai data:",
        res.data
      )


      setRowsFromDB(
        res.data
      )


      /*
       * 最終更新情報取得
       */
      await loadLastSaved()


      /*
       * 初期フォーカス
       */
      await nextTick()


      focusCell(
        0,
        0
      )


      /*
       * 初期表示終了
       */
      isInitializing.value = false

    } catch (e) {

      console.error(e)

      alert(
        "データ取得に失敗しました"
      )

      isInitializing.value = false

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

    markMenu.value.visible =
      false

  }
)


/* =========================
   Dirty
========================= */

function markDirty(row) {

  row._dirty =
    true

}


/* =========================
   マーカー切替
========================= */

async function toggleMarker() {

  const row =
    markMenu.value.row

  const markNo =
    markMenu.value.markNo


  if (
    !row ||
    !markNo
  ) {

    return

  }


  const key =
    `mark${markNo}`


  /*
   * 0 → 1
   * 1 → 0
   */
  row[key] =
    Number(row[key]) === 1
      ? 0
      : 1


  markMenu.value.visible =
    false


  /*
   * マーカー変更は強制保存
   */
  await saveRow(
    row,
    true
  )

}

</script>


<style scoped>

.grid {

  padding: 20px;

  display: flex;

  justify-content: center;

  width: 100%;

  box-sizing: border-box;

  min-height: 100vh;

  min-height: 100dvh;

  overflow: hidden;

}


.content-area {

  display: flex;

  flex-direction: column;

  align-items: stretch;

  width: 100%;

  min-width: 0;

  min-height: 0;

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

  color: #000;

  margin-bottom: 2px;

}


/* =========================
   テーブル
========================= */

.table-wrap {

  overflow-x: auto;

  overflow-y: auto;

  width: 100%;

  max-width: 100%;

  max-height:
    calc(100vh - 80px);

  max-height:
    calc(100dvh - 80px);

  min-width: 0;

  min-height: 0;

  -webkit-overflow-scrolling: touch;

  touch-action:
    pan-x pan-y;

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


.col-jissiyear {

  width: 100px;

}


.col-seisansha {

  width: 110px;

}


.col-shinjakb {

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

  width: 48px;

  min-width: 48px;

  max-width: 48px;

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


.jissiyear-header,
.jissiyear-cell {

  width: 100px;

  min-width: 100px;

  max-width: 100px;

}


.seisansha-header,
.seisansha-cell {

  width: 162px;

  min-width: 162px;

  max-width: 162px;

}


.shinjakb-header,
.shinjakb-cell {

  width: 70px;

  min-width: 70px;

  max-width: 70px;

}


.suryo-header,
.suryo-cell {

  width: 280px;

  min-width: 280px;

  max-width: 280px;

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

  width: 204px;

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


.jissiyear-input {

  width: 95px;

  text-align: center;

}


.seisansha-input {

  width:158px;

  text-align: left;

}


.shinjakb-select {

  width: 65px;

  text-align: center;

}


.suryo-input {

  width: 275px;

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
    rgba(0, 0, 0, 0.15);

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


/* =========================
   マーカー
========================= */

.marked {

  background-color:
    rgb(255, 255, 255) !important;

  color: white;

}


.marked input,
.marked select {

  background-color:
    rgb(255, 178, 195) !important;

  color: white;

}


.marked input::placeholder {

  color: white;

}

</style>