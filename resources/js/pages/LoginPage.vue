<template>
  <div class="login-wrapper">
    <div class="login-card">
      <h1 class="title">ログイン</h1>

      <div class="form-group">
        <label>所属</label>
        <select v-model="pref">
          <option value="">選択してください</option>
          <option v-for="p in prefectures" :key="p" :value="p">
            {{ p }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>パスワード</label>
        <input
          type="password"
          v-model="password"
          placeholder="パスワードを入力"
        />
      </div>

      <button class="login-btn" @click="login">
        ログイン
      </button>

      <p v-if="error" class="error-msg">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import axios from "axios"

const router = useRouter()

const prefectures = [
    "京都","東京","玉手山","神崎","西宮","神戸","本山",
    "福山","札幌","名古屋","豊中","広島","熊本","高知",
    "福岡","千葉","奈良","姫路","滋賀","松山","北九州",
    "日根野","岡山","仙台","新潟","水戸","静岡","豊橋",
    "富山","岐阜","金沢","山口","高松","徳島","大分",
    "長崎","宇都宮","松本","青森","盛岡","秋田","米子",
    "旭川","豊田","徳山","浜松","さいたま","横浜","さがみ",
    "八王子","須磨","宮崎","呉","松江","交野","日立",
    "近江八幡","鹿児島","福井","足立","鳥取","高崎",
    "三重","所沢","佐賀","福島","山形","沖縄"
]

const pref = ref("")
const password = ref("")
const error = ref("")

const login = async () => {
  if (!pref.value) {
    error.value = "都道府県を選択してください"
    return
  }

  if (password.value !== "1234") {
    error.value = "パスワードが違います"
    return
  }

  localStorage.setItem("loggedIn", "1")

  const res = await axios.get(
    "/api/shozoku",
    {
      params: {
        sname: pref.value
      }
    }
  )

  localStorage.setItem(
    "shozokuid",
    res.data.no
  )

  localStorage.setItem("pref", pref.value)

  router.push("/select")
}
</script>

<style scoped>
.login-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: #eef3ff;
}

.login-card {
  width: 400px;
  background: white;
  padding: 30px 35px;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.title {
  text-align: center;
  font-size: 26px;
  margin-bottom: 20px;
  color: #1f3a8a;
}

.form-group {
  margin-bottom: 18px;
}

label {
  display: block;
  margin-bottom: 6px;
  font-weight: bold;
}

select,
input {
  width: 100%;
  padding: 9px 10px;
  border-radius: 6px;
  border: 1px solid #999;
  font-size: 14px;
  background: #fff;
}

.login-btn {
  width: 100%;
  padding: 10px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  cursor: pointer;
}

.login-btn:hover {
  background: #1d4ed8;
}

.error-msg {
  margin-top: 15px;
  background: #ffcccc;
  color: #b00000;
  padding: 8px;
  border-radius: 6px;
  text-align: center;
}
</style>
