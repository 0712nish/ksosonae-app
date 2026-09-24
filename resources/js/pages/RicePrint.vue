<template>
  <div class="print-area">
    <h1 class="report-title">
      感謝祭お供え報告書
    </h1>
    <div class="report-subtitle">
      所属【 {{ sname }} 】
    </div>
    <div class="rice-title">
      ◆お米
    </div>
    <table class="rice-table">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th>数量</th>
          <th>左記以外×数量</th>
        </tr>
      </thead>
      <tbody>
        <!-- うるち 俵 -->
        <tr>
          <td rowspan="2">うるち</td>
          <td>俵(60kg)</td>
          <td>
            {{ rice.tawara1 }}　俵
          </td>
          <td>
            {{ rice.tawara3 }}
          </td>
        </tr>
        <!-- うるち 袋 -->
        <tr>
          <td>袋(30kg)</td>
          <td>
            {{ rice.fukuro1 }}　袋
          </td>
          <td>
            {{ rice.fukuro3 }}
          </td>
        </tr>
        <!-- もち 俵 -->
        <tr>
          <td rowspan="2">もち</td>
          <td>俵(60kg)</td>
          <td>
            {{ rice.tawara2 }}　俵
          </td>
          <td>
            {{ rice.tawara4 }}
          </td>
        </tr>
        <!-- もち 袋 -->
        <tr>
          <td>袋(30kg)</td>
          <td>
            {{ rice.fukuro2 }}　袋
          </td>
          <td>
            {{ rice.fukuro4 }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>

import { ref, onMounted, nextTick } from "vue"

const sname = ref("")

const rice = ref({
  tawara1: "",
  tawara2: "",
  tawara3: "",
  tawara4: "",

  fukuro1: "",
  fukuro2: "",
  fukuro3: "",
  fukuro4: ""
})


onMounted(async () => {

  const data = localStorage.getItem("printRice")

  if (data) {

    const parsed = JSON.parse(data)

    sname.value = parsed.sname || ""

    rice.value = parsed.rice || {
      tawara1: "",
      tawara2: "",
      tawara3: "",
      tawara4: "",

      fukuro1: "",
      fukuro2: "",
      fukuro3: "",
      fukuro4: ""
    }

  }

  await nextTick()

  window.print()

})

</script>

<style scoped>

.print-area {
  padding: 20px;
}

/* ===== タイトル ===== */

.report-title {
  text-align: center;
  font-size: 32px;
  font-weight: bold;
  letter-spacing: 4px;
  margin-bottom: 20px;
  color: #333;
}

.report-subtitle {
  font-size: 20px;
  font-weight: bold;
  color: #020080;
  margin-bottom: 25px;
}

.rice-title {
  font-size: 22px;
  font-weight: bold;
  color: #020080;
  margin-bottom: 10px;
}

/* ===== お米テーブル ===== */

.rice-table {
  width: 800px;
  border-collapse: collapse;
}

.rice-table th,
.rice-table td {
  border: 1px solid #000;
  text-align: center;
  vertical-align: middle;
}

.rice-table th {
  background: #f5f5f5;
  padding: 10px;
  font-size: 16px;
}

.rice-table td {
  height: 60px;
  font-size: 18px;
}

/* ===== 印刷 ===== */

@media print {

  body {
    margin: 0;
  }

  .print-area {
    padding: 0;
  }

  @page {
    size: A4 portrait;
    margin: 15mm;
  }

}

</style>
