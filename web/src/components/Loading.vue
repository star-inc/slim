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
        <v-progress-circular
            indeterminate
            color="red"
        />
      </v-card-title>
    </v-card>
    <v-card outlined rounded>
      <v-card-title>
        Slim 是一個自由的開源 ERP 框架，提供開發人員儘可能簡單地建立出屬於他們的系統。
      </v-card-title>
      <v-card-subtitle>
        Slim is a free and opensource ERP Framework for developers to create their system as simple as possible.
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
  name: "Loading"
}
</script>
