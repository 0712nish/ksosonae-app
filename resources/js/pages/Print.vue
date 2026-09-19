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
            <th rowspan="2">No</th>
            <th rowspan="2">区別</th>
            <th rowspan="2">品目</th>

            <th colspan="3">20日</th>
            <th colspan="3">25日</th>
            <th colspan="3">28日</th>
        </tr>

        <tr>
            <th>単位</th>
            <th>合計</th>
            <th>箱</th>

            <th>単位</th>
            <th>合計</th>
            <th>箱</th>

            <th>単位</th>
            <th>合計</th>
            <th>箱</th>
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
import { onMounted, ref, nextTick } from "vue"

const rows = ref([])
const sname = ref("")

onMounted(async () => {

  rows.value = JSON.parse(
    localStorage.getItem("printRows")
  ) || []

  sname.value = localStorage.getItem("printSname") || ""

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
}

@media print {

  body{
    margin:0;
  }

  @page{
    size:A4 landscape;
  }
}
</style>
