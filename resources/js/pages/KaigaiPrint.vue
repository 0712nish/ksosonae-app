<template>
  <div class="print-area">

    <h1 class="report-title">
      感謝祭お供え報告書
    </h1>

    <div class="report-subtitle">
      所属【 {{ sname }} 】
    </div>

    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>区別</th>
          <th>品目</th>
          <!-- 香港の場合だけ表示 -->
          <th v-if="isHongKong">中国語</th>
          <th>地域名</th>
          <th>自然農法<br>実施年数</th>
          <th>生産者名</th>
          <th>信者<br>未信者</th>
          <th colspan="4">数量</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="row in rows"
          :key="row.autono || row.no"
        >
          <td>{{ row.no }}</td>
          <td>{{ row.kubetsu }}</td>
          <td>{{ row.hinmoku }}</td>
          <!-- 香港の場合だけ表示 -->
          <td v-if="isHongKong">{{ row.chugokugo }}</td>
          <td>{{ row.chiikimei }}</td>
          <td>{{ row.jissiyear }}</td>
          <td>{{ row.seisansha }}</td>
          <td>{{ row.shinjakb }}</td>
          <!-- 数量の4項目 -->
          <td class="quantity-cell">{{ formatQuantity(row.tanikosu) }}</td>
          <td class="quantity-cell">{{ row.tstani }}</td>
          <td class="quantity-cell">{{ formatQuantity(row.gokeisu) }}</td>
          <td class="quantity-cell">{{ row.gstani }}</td>
        </tr>
      </tbody>
    </table>

  </div>
</template>

<script setup>
import { onMounted, ref, nextTick , computed } from "vue"

const rows = ref([])
const sname = ref("")

const isHongKong = computed(() => {
  return sname.value.includes("香港")
})

onMounted(async () => {

  rows.value = JSON.parse(
    localStorage.getItem("printKaigaiRows")
  ) || []

  sname.value =
    localStorage.getItem("printKaigaiSname") || ""

  await nextTick()

  window.print()
})

const formatQuantity = (value) => {
  if (value === null || value === undefined || value === "") {
    return ""
  }

  const num = Number(value)

  if (!Number.isFinite(num)) {
    return value
  }

  return Number.isInteger(num)
    ? String(num)
    : String(num)
}

</script>

<style scoped>

.print-area {
  padding: 20px;
}

.report-title {
  text-align: center;
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 10px;
}

.report-subtitle {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 15px;
}

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

th,
td {
  border: 1px solid #000;
  padding: 4px 2px;
  font-size: 10px;
  text-align: center;
  vertical-align: middle;
  overflow-wrap: anywhere;
}

@media print {
  @page {
    size: A4 landscape;
    margin: 7mm;
  }

  .print-area {
    padding: 0;
  }

  table {
    width: 100%;
  }

  th,
  td {
    font-size: 9px;
    padding: 3px 1px;
  }

  thead {
    display: table-header-group;
  }

  tr {
    break-inside: avoid;
    page-break-inside: avoid;
  }
}

.quantity-cell {
  font-size: 15px;
  /*font-weight: bold;*/
}

@media print {
  .quantity-cell {
    font-size: 14px;
    /*font-weight: bold;*/
  }
}

</style>
