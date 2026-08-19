<template>
  <div class="select-wrapper">

    <div class="select-card">
      <h2 class="title">お米 or 野菜を選択してください</h2>

      <div class="radio-group">

        <label class="radio-item">
          <input type="radio" value="1" v-model="selected" />
          <span>お米</span>
        </label>

        <label class="radio-item">
          <input type="radio" value="2" v-model="selected" />
          <span>野菜,果物,特産品</span>
        </label>

      </div>

<!--
      <h2 class="title">入力する日付を選択してください</h2>

      <div class="radio-group">

        <label class="radio-item">
          <input type="radio" value="20" v-model="selected" />
          <span>20日</span>
        </label>

        <label class="radio-item">
          <input type="radio" value="25" v-model="selected" />
          <span>25日</span>
        </label>

        <label class="radio-item">
          <input type="radio" value="28" v-model="selected" />
          <span>28日</span>
        </label>

        <label class="radio-item">
          <input type="radio" value="ALL" v-model="selected" />
          <span>ALL</span>
        </label>

      </div>
-->
      <button class="next-btn" @click="goNext">
        次へ
      </button>

    </div>

  </div>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()
const selected = ref("ALL")

const sname = localStorage.getItem("pref")
const shozokuid = localStorage.getItem("shozokuid")

const goNext = () => {

  if (selected.value === "1") {

    router.push({
      path: "/ricegrid",
      query: {
        sname: sname,
        shozokuid: shozokuid
      }
    })

  } else {

    router.push({
      //path: "/grid",
      path: "/kaigaigrid",
      query: {
        // mode: selected.value,
        mode: "ALL",
        sname: sname,
        shozokuid: shozokuid
      }
    })

  }

}
</script>

<style scoped>
.select-wrapper {
  background: #eef3ff;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.select-card {
  width: 480px;
  background: #fff;
  padding: 35px 40px;
  border-radius: 18px;
  box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

.title {
  margin: 0 0 20px 0;
  font-size: 22px;
  color: #1f3a8a;
  text-align: center;
}

.radio-group {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 25px;
}

.radio-item {
  display: flex;
  align-items: center;
  padding: 10px 12px;
  border: 1px solid #c9d8ff;
  border-radius: 10px;
  background: #f7f9ff;
  cursor: pointer;
}

.radio-item:hover {
  background: #eaf0ff;
}

.radio-item input {
  transform: scale(1.2);
  margin-right: 8px;
}

.next-btn {
  width: 100%;
  padding: 12px;
  background: #2563eb;
  border: none;
  color: white;
  font-size: 17px;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.2s;
}

.next-btn:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}
</style>
