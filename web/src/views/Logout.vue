<template>
  <v-container>
    <v-card flat>
      <v-card-title>
        登出 Slim ERP
      </v-card-title>
      <v-card-subtitle>
        Logout Slim ERP
      </v-card-subtitle>
      <v-card-actions>
        <v-btn :disabled="loading" @click="cancel" depressed>取消</v-btn>
        <v-spacer/>
        <v-btn :loading="loading" @click="submit" color="red white--text" depressed>登出</v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: "Logout",
  data: () => ({
    loading: false,
  }),
  methods: {
    cancel() {
      if (this.$router.history.length) {
        this.$router.back()
      } else {
        this.$router.replace("/")
      }
    },
    async submit() {
      this.loading = true;
      try {
        await this.$axios.delete("/authentic/session")
      } catch (e) {
        console.warn(e)
      }
      location.assign(location.pathname)
    }
  }
}
</script>
