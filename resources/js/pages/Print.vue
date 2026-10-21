<template>
  <div class="print-area">

    <h1 class="report-title">
      感謝祭お供え報告書
    </h1>

    <div class="report-subtitle">
      所属【 {{ sname }} 】
    </div>
    <div class="print-save-info">
      最終更新 {{ lastSavedAt }}<br>
      入力者：{{ lastSavedTantoshaname }}
    </div>
    <div class="report-subtitle">
      ◆野菜、果物、特産品
    </div>
    <table>
        <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">区別</th>
            <th rowspan="2">品目</th>

            <th colspan="3">20日</th>
            <th colspan="3">25日</th>
            <th colspan="3">28日</th>
        </tr>

        <tr>
            <th>1単位の内容量</th>
            <th>1箱の数量</th>
            <th>箱数</th>

            <th>1単位の内容量</th>
            <th>1箱の数量</th>
            <th>箱数</th>

            <th>1単位の内容量</th>
            <th>1箱の数量</th>
            <th>箱数</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in rows" :key="row.no">

            <td>{{ row.no }}</td>

            <td>{{ row.kubetsu }}</td>

            <td>{{ row.hinmoku }}</td>

            <!-- 20日 -->
            <td>
            {{ row.quantities['20日'].ml_value }}
            {{ row.quantities['20日'].ml_unit }}
            </td>

            <td>
            {{ row.quantities['20日'].hon_value }}
            {{ row.quantities['20日'].hon_unit }}
            </td>

            <td>
            {{ row.quantities['20日'].hako_value }}
            {{ row.quantities['20日'].hako_unit }}
            </td>

            <!-- 25日 -->
            <td>
            {{ row.quantities['25日'].ml_value }}
            {{ row.quantities['25日'].ml_unit }}
            </td>

            <td>
            {{ row.quantities['25日'].hon_value }}
            {{ row.quantities['25日'].hon_unit }}
            </td>

            <td>
            {{ row.quantities['25日'].hako_value }}
            {{ row.quantities['25日'].hako_unit }}
            </td>

            <!-- 28日 -->
            <td>
            {{ row.quantities['28日'].ml_value }}
            {{ row.quantities['28日'].ml_unit }}
            </td>

            <td>
            {{ row.quantities['28日'].hon_value }}
            {{ row.quantities['28日'].hon_unit }}
            </td>

            <td>
            {{ row.quantities['28日'].hako_value }}
            {{ row.quantities['28日'].hako_unit }}
            </td>

        </tr>
        </tbody>
    </table>

  </div>
</template>


<script setup>
import axios from "axios"
import { onMounted, ref, nextTick } from "vue"

const rows = ref([])
const sname = ref("")

const lastSavedAt = ref("")
const lastSavedTantoshaname = ref("")

async function loadLastSaved() {
  try {
    const shozokuid = localStorage.getItem("shozokuid")

    // 一覧画面で保存した年度があればそれを使用
    const savedYear = localStorage.getItem("printYear")
    const year = savedYear || new Date().getFullYear()

    const res = await axios.get("/api/osonae/last-saved", {
      params: {
        shozokuid,
        year
      }
    })

    lastSavedAt.value = res.data?.updatedt ?? ""
    lastSavedTantoshaname.value =
      res.data?.tantoshaname ?? ""
  } catch (e) {
    console.error("最終更新情報取得失敗", e)
    lastSavedAt.value = ""
    lastSavedTantoshaname.value = ""
  }
}

onMounted(async () => {
  rows.value = JSON.parse(
    localStorage.getItem("printRows") || "[]"
  )

  sname.value =
    localStorage.getItem("printSname") || ""

  // 最終更新情報を取得してから印刷
  await loadLastSaved()
  await nextTick()

  window.print()
})
</script>

<style scoped>

.print-area{
  padding:20px;
}

table{
  width:100%;
  border-collapse:collapse;
}

th,td{
  border:1px solid #000;
  padding:6px;
  font-size:12px;
  text-align: center;
}

@media print {

  body{
    margin:0;
  }

  @page{
    size:A4 landscape;
  }
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

.print-save-info {
  text-align: right;
  font-size: 11px;
  color: #000;
  margin-bottom: 8px;
}

</style>
