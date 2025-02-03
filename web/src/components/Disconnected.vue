<template>
  <v-container>
    <v-card flat>
      <v-card-title>
        <v-avatar color="grey lighten-5">
          <v-img
              max-width="15"
              max-height="35"
              :src="require('../assets/logo.png')"
          />
        </v-avatar>
        <v-card-title>
          Slim ERP Framework
        </v-card-title>
      </v-card-title>
    </v-card>
    <v-card outlined rounded>
      <v-card-title>
        Slim 與後端伺服器通訊失敗，伺服器可能尚未設定、網路失效，或遇到未知錯誤，請稍後再試。
      </v-card-title>
      <v-card-subtitle>
        Slim failed to connect with the backend server. The server may not be configured, the network is down, or an unknown error has been encountered. Please try again later.
      </v-card-subtitle>
      <v-card-subtitle>
        &copy; Slim 版權為
        <a class="text-decoration-none" href="https://starinc.xyz">Star Inc.</a>
        所有，以 MIT 條款開放原始碼授權。
      </v-card-subtitle>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: "Disconnected"
}
</script>
