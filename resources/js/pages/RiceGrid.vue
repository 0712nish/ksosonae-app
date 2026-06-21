<template>
  <div class="rice-page">

    <div class="header-area">
      <div class="title-area">
        <h1 class="report-title">感謝祭お供え報告書</h1>

        <div class="report-subtitle">
          所属【 {{ sname }} 】
        </div>
      </div>

      <button class="print-btn" @click="printTable">
        印刷
      </button>
    </div>

    <div class="save-info">
      最終更新 {{ lastSavedAt }}
    </div>

    <div class="report-subtitle rice-title">
      ◆お米
    </div>

    <table class="rice-table">

      <tr>
        <th>うるち</th>
        <th>数量</th>
        <th>もち</th>
        <th>数量</th>
        <th>左記以外×数量</th>
        <th>左記以外×数量</th>
      </tr>

      <tr>
        <td>俵(60kg)</td>

        <td>
          <input
            type="number"
            v-model="rice.uruchi_tawara"
          >
        </td>

        <td>俵(60kg)</td>

        <td>
          <input
            type="number"
            v-model="rice.mochi_tawara"
          >
        </td>

        <td></td>

        <td>
          <input
            type="number"
            v-model="rice.other_tawara"
          >
        </td>
      </tr>

      <tr>
        <td>袋(30kg)</td>

        <td>
          <input
            type="number"
            v-model="rice.uruchi_fukuro"
          >
        </td>

        <td>袋(30kg)</td>

        <td>
          <input
            type="number"
            v-model="rice.mochi_fukuro"
          >
        </td>

        <td></td>

        <td>
          <input
            type="number"
            v-model="rice.other_fukuro"
          >
        </td>
      </tr>

    </table>

  </div>
</template>

<script setup>
import { reactive, ref } from "vue"

const sname = localStorage.getItem("pref")

const lastSavedAt = ref("")

const rice = reactive({
  uruchi_tawara: "",
  uruchi_fukuro: "",

  mochi_tawara: "",
  mochi_fukuro: "",

  other_tawara: "",
  other_fukuro: ""
})
</script>

<style scoped>

.header-area {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 10px;
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
  font-size: 25px;
  font-weight: bold;
  color: #020080;
}

.print-btn {
  padding: 4px 12px;
  background: #f5f5f5;
  border: 1px solid #bbb;
  border-radius: 4px;
  font-size: 13px;
  cursor: pointer;
}

.print-btn:hover {
  background: #eaeaea;
}

.save-info {
  text-align: right;
  font-size: 11px;
  color: #777;
  margin-bottom: 2px;
}

.rice-page {
  padding: 20px;
}

.rice-table {
  border-collapse: collapse;
  width: 1000px;
}

.rice-table th,
.rice-table td {
  border: 1px solid #000;
}

.rice-table th {
  background: #f5f5f5;
  text-align: center;
  padding: 10px;
}

.rice-table td {
  height: 80px;
  vertical-align: top;
  padding: 8px;
}

.rice-table input {
  width: 80px;
  text-align: right;
}

.rice-title {
  margin-top: 20px;
  margin-bottom: 10px;
}

</style>
