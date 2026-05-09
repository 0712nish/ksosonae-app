<template>
  <div>
    <h1 style="color:blue; font-size:30px;margin-top:20px; margin-left:200px; margin-bottom:5px;">感謝祭お供え報告書</h1>
    <h2 style="color:black; font-size:22px;margin-top:5px; margin-left:40px; margin-bottom:5px;">京都支部</h2>
    <div style="color:black; font-size:22px;margin-top:5px; margin-left:40px; margin-bottom:5px;">　◇お米</div>
    <RiceTable />

    <!-- ★ここが重要 -->
      <div style="color:black; font-size:22px;margin-top:5px; margin-left:40px; margin-bottom:15px;">　◇野菜・果物・特産品</div>
    <TabulatorTable />
  </div>
</template>

<script setup>
import RiceTable from '../components/RiceTable.vue'
import TabulatorTable from '../components/TabulatorTable.vue'
</script>