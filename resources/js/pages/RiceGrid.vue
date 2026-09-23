<template>
  <div class="rice-page">

    <div class="header-area">
      <button class="back-btn" @click="goBack">戻る</button>
      <div class="title-area">
        <h1 class="report-title">感謝祭お供え報告書</h1>

        <div class="report-subtitle">
          所属【　{{ sname }}　】
        </div>
      </div>

      <button class="print-btn" @click="printTable">
        印刷
      </button>

    </div>

    <div class="save-info">
      最終更新 {{ lastSavedAt }}<br>
      担当者：{{ lastSavedTantoshaname }}
    </div>

    <div class="report-subtitle rice-title">
      ◆お米
    </div>

<table class="rice-table">

  <tr>
    <th></th>
    <th></th>
    <th>数量</th>
    <th>左記以外×数量</th>
  </tr>

  <tr>
    <td rowspan="2">うるち</td>
    <td>俵(60kg)</td>
    <td class="input-cell">
      <input
        type="number"
        v-model="rice.tawara1"
        @input="markDirty"
        @blur="saveRice"
      >
    </td>
    <td>
      <input
        type="text"
        maxlength="30"
        v-model="rice.tawara3"
        @input="markDirty"
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
        @input="markDirty"
        @blur="saveRice"
      >
    </td>
    <td>
      <input
        type="text"
        maxlength="30"
        v-model="rice.fukuro3"
        @input="markDirty"
        @blur="saveRice"
      >
    </td>
  </tr>
  <tr>
    <td rowspan="2">もち</td>
    <td>俵(60kg)</td>
    <td class="input-cell">
      <input
        type="number"
        v-model="rice.tawara2"
        @input="markDirty"
        @blur="saveRice"
      >
    </td>
    <td>
      <input
        type="text"
        maxlength="30"
        v-model="rice.tawara4"
        @input="markDirty"
        @blur="saveRice"
      >
    </td>
  </tr>

  <tr>
    <td>袋(30kg)</td>
    <td class="input-cell">
      <input
        type="number"
        v-model="rice.fukuro2"
        @input="markDirty"
        @blur="saveRice"
      >
    </td>
    <td>
      <input
        type="text"
        maxlength="30"
        v-model="rice.fukuro4"
        @input="markDirty"
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
import { useRouter } from "vue-router"

const router = useRouter()

const sname = localStorage.getItem("pref")
const shozokuid = localStorage.getItem("shozokuid")
const tantoshaname = localStorage.getItem("tantoshaname") || ""
const year = 2025

const lastSavedAt = ref("")
const lastSavedTantoshaname = ref("")

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

const isDirty = ref(false)

function markDirty() {
  isDirty.value = true
}

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
  lastSavedTantoshaname.value = res.data.tantoshaname ?? ""

  // DBから読み込んだだけなので保存対象ではない
  isDirty.value = false
  
}

async function saveRice() {

  const res = await axios.post(
    "/api/osonaerice/save",
    {
      shozokuid,
      year,

      // 保存した担当者
      tantoshaname,

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

  lastSavedAt.value = res.data.updatedt ?? ""
  lastSavedTantoshaname.value = res.data.tantoshaname ?? tantoshaname

  // 保存できたのでdirty解除
  isDirty.value = false

}

onMounted(async () => {
  await loadRice()
})

function goBack() {
  router.back()
}

function printTable() {

  localStorage.setItem(
    "printRice",
    JSON.stringify({
      sname,
      rice: {
        tawara1: rice.tawara1,
        tawara2: rice.tawara2,
        tawara3: rice.tawara3,
        tawara4: rice.tawara4,

        fukuro1: rice.fukuro1,
        fukuro2: rice.fukuro2,
        fukuro3: rice.fukuro3,
        fukuro4: rice.fukuro4
      }
    })
  )

  const url = router.resolve({
    path: "/rice-print"
  }).href

  window.open(url, "_blank")
}

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

.print-btn, .back-btn {
  padding: 8px 20px;

  background: #f5f5f5;
  border: 1px solid #bbb;
  border-radius: 4px;

  font-size: 16px;
  font-weight: bold;

  cursor: pointer;
}

.print-btn:hover, .back-btn:hover {
  background: #eaeaea;
}

.print-btn:active, .back-btn:active {
  transform: translateY(0);
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
  margin: 0 auto;
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
