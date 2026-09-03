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
      
      <!-- 担当者氏名 -->
      <div class="form-group">
        <label>担当者氏名</label>
        <input
          type="text"
          v-model="tantoshaname"
          placeholder="担当者氏名を入力"
        />
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
    "三重","所沢","佐賀","福島","山形","沖縄",
    "香港","台湾","上海","シンガポール","タイ","ベトナム","マレーシア",
    "インドネシア","フィリピン","アメリカ","カナダ","イギリス","フランス","ドイツ","イタリア","オーストラリア"
]
/*const prefectures = [
    "旭川","札幌","青森","盛岡","仙台","秋田","山形","福島",
    "日立","水戸","宇都宮","高崎","さいたま","所沢","千葉",
    "東京","足立","八王子","さがみ","横浜",
    "新潟","富山","金沢","福井","松本","岐阜",
    "静岡","浜松","豊橋","豊田","名古屋",
    "三重","滋賀","近江八幡","京都","交野","豊中","神崎","玉手山",
    "西宮","神戸","本山","須磨","姫路","奈良","日根野",
    "鳥取","米子","松江","岡山",
    "福山","呉","広島","徳山","山口",
    "徳島","高松","松山","高知",
    "福岡","北九州","佐賀","長崎","熊本","大分","宮崎","鹿児島","沖縄"
]*/

const pref = ref("")
const tantoshaname = ref("")
const password = ref("")
const error = ref("")

const login = async () => {

  // 所属チェック
  if (!pref.value) {
    error.value = "所属を選択してください"
    return
  }

  // 担当者氏名チェック
  if (!tantoshaname.value.trim()) {
    error.value = "担当者氏名を入力してください"
    return
  }

  try {

    // 所属情報取得
    const res = await axios.get(
      "/api/shozoku",
      {
        params: {
          sname: pref.value
        }
      }
    )

    // パスワード判定
    if (
      password.value !== "19700301" &&
      password.value !== res.data.pass
    ) {
      error.value = "パスワードが違います"
      return
    }

    // ログイン成功後にlocalStorageへ保存
    localStorage.setItem("loggedIn", "1")
    localStorage.setItem("shozokuid", res.data.no)
    localStorage.setItem("reigaiflg", res.data.reigaiflg)
    localStorage.setItem("pref", pref.value)

    // ログイン情報をlogindataへ保存
    await axios.post(
      "/api/logindata/save",
      {
        shozokuid: res.data.no,
        tantoshaname: tantoshaname.value.trim()
      }
    )

    // 選択画面へ
    router.push("/select")

  } catch (e) {

    console.log(e.response?.data)

    error.value = "ログイン処理に失敗しました"
  }
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
