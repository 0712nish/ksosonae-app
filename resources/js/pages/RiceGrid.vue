<template>
  <div class="rice-page">

    <div class="header-area">
      <div class="title-area">
        <h1 class="report-title">感謝祭お供え報告書</h1>

        <div class="report-subtitle">
          所属【 {{ sname }} 】
        </div>
      </div>

      <!--
      <button class="print-btn" @click="printTable">
        印刷
      </button>
      -->
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

    <td class="input-cell">
      <input
        type="number"
        v-model="rice.tawara1"
        @blur="saveRice"
      >
    </td>

    <td>俵(60kg)</td>

    <td class="input-cell">
      <input
        type="number"
        v-model="rice.tawara2"
        @blur="saveRice"
      >
    </td>

    <td>
      <input
        type="text"
        maxlength="30"
        v-model="rice.tawara3"
        @blur="saveRice"
      >
    </td>

    <td class="input-cell">
      <input
        type="text"
        maxlength="30"
        v-model="rice.tawara4"
        @blur="saveRice"
      >
    </td>
  </tr>

  <tr>
    <td>袋(30kg)</td>

    <td class="input-cell">
      <input
        type="number"
        v-model="rice.fukuro1"
        @blur="saveRice"
      >
    </td>

    <td>袋(30kg)</td>

    <td class="input-cell">
      <input
        type="number"
        v-model="rice.fukuro2"
        @blur="saveRice"
      >
    </td>

    <td>
      <input
        type="text"
        maxlength="30"
        v-model="rice.fukuro3"
        @blur="saveRice"
      >
    </td>

    <td class="input-cell">
      <input
        type="text"
        maxlength="30"
        v-model="rice.fukuro4"
        @blur="saveRice"
      >
    </td>
  </tr>

</table>

  </div>
</template>

<script setup>
import axios from "axios"
import { reactive, ref, onMounted } from "vue"

const sname = localStorage.getItem("pref")
const shozokuid = localStorage.getItem("shozokuid")
const year = 2025

const lastSavedAt = ref("")

const rice = reactive({
  tawara1: "",
  tawara2: "",
  tawara3: "",
  tawara4: "",

  fukuro1: "",
  fukuro2: "",
  fukuro3: "",
  fukuro4: ""
})

async function loadRice() {

  const res = await axios.get(
    "/api/osonaerice",
    {
      params: {
        shozokuid,
        year
      }
    }
  )

  if (!res.data) {
    return
  }

  rice.tawara1 = res.data.tawara1 ?? ""
  rice.tawara2 = res.data.tawara2 ?? ""
  rice.tawara3 = res.data.tawara3 ?? ""
  rice.tawara4 = res.data.tawara4 ?? ""

  rice.fukuro1 = res.data.fukuro1 ?? ""
  rice.fukuro2 = res.data.fukuro2 ?? ""
  rice.fukuro3 = res.data.fukuro3 ?? ""
  rice.fukuro4 = res.data.fukuro4 ?? ""

  lastSavedAt.value = res.data.updatedt ?? ""
}

async function saveRice() {

  const res = await axios.post(
    "/api/osonaerice/save",
    {
      shozokuid,
      year,

      tawara1: rice.tawara1,
      tawara2: rice.tawara2,
      tawara3: rice.tawara3,
      tawara4: rice.tawara4,

      fukuro1: rice.fukuro1,
      fukuro2: rice.fukuro2,
      fukuro3: rice.fukuro3,
      fukuro4: rice.fukuro4
    }
  )

  lastSavedAt.value = res.data.updatedt
}

onMounted(async () => {
  await loadRice()
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
  vertical-align: middle;
  text-align: center;
}

.rice-table input {
  width: 80px;
  text-align: right;
}

.rice-title {
  margin-top: 20px;
  margin-bottom: 10px;
}

.rice-table input[type="text"] {
  width: 200px;
  display: block;
  margin: 0 auto;
  font-size: 18px;
}

.rice-table input[type="number"] {
  width: 80px;
  display: block;
  margin: 0 auto;
  font-size: 18px;
}

</style>
