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
        Slim ERP 登入界面
      </v-card-title>
      <v-card-subtitle>
        Slim ERP Login
      </v-card-subtitle>
      <v-card-subtitle class="red white--text" v-show="message" v-text="message"/>
      <v-card-actions v-if="!id_login">
        <v-btn :disabled="true" depressed>
          單一簽入認證
        </v-btn>
        <v-spacer/>
        <v-btn @click="id_login = true" class="secondary" depressed>
          以帳號密碼登入
        </v-btn>
      </v-card-actions>
      <div v-else>
        <v-card-text>
          <v-text-field v-model="username" label="帳號" @keydown.enter="$refs.password.focus" type="text"/>
          <v-text-field v-model="password" label="密碼" ref="password" @keydown.enter="submit" type="password"/>
        </v-card-text>
        <v-card-actions>
          <v-btn :disabled="loading" @click="id_login = false" depressed>取消</v-btn>
          <v-spacer/>
          <v-btn :loading="loading" @click="submit" class="secondary" depressed>登入</v-btn>
        </v-card-actions>
      </div>
    </v-card>
  </v-container>
</template>

<script>
import capitalize from "capitalize";

export default {
  name: "Login",
  data: () => ({
    loading: false,
    id_login: false,
    message: null,
    username: null,
    password: null,
  }),
  methods: {
    async submit() {
      this.loading = true;
      try {
        const form = new URLSearchParams();
        form.set("username", this.username);
        form.set("password", this.password);
        const response = await this.$axios.post("/authentic/session", form)
        if (response.status === 201) this.$emit("success")
      } catch (e) {
        this.message = e.response.data.message 
          ? capitalize(e.response.data.message)
          : "Failed"
        console.warn(e)
      }
      this.loading = false;
    }
  }
}
</script>
