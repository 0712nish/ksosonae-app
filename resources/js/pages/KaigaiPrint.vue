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
          <th>中国語</th>
          <th>地域名</th>
          <th>自然農法<br>実施年数</th>
          <th>生産者名</th>
          <th>信者<br>未信者</th>
          <th>数量</th>
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
          <td>{{ row.chugokugo }}</td>
          <td>{{ row.chiikimei }}</td>
          <td>{{ row.jissiyear }}</td>
          <td>{{ row.seisansha }}</td>
          <td>{{ row.shinjakb }}</td>
          <td>{{ row.suryo }}</td>
        </tr>
      </tbody>
    </table>

  </div>
</template>

<script setup>
import { onMounted, ref, nextTick } from "vue"

const rows = ref([])
const sname = ref("")

onMounted(async () => {

  rows.value = JSON.parse(
    localStorage.getItem("printKaigaiRows")
  ) || []

  sname.value =
    localStorage.getItem("printKaigaiSname") || ""

  await nextTick()

  window.print()
})
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
  padding: 5px;
  font-size: 11px;
  text-align: center;
  vertical-align: middle;
  word-break: break-all;
}

th {
  background: #f5f5f5;
  font-weight: bold;
}

@media print {

  body {
    margin: 0;
  }

  @page {
    size: A4 landscape;
    margin: 10mm;
  }

  .print-area {
    padding: 0;
  }

  table {
    width: 100%;
  }

  th,
  td {
    font-size: 10px;
  }
}

</style>
