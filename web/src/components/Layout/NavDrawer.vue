<template>
  <v-navigation-drawer v-model="drawer" absolute color="black lighten-3" mini-variant>
    <profile/>
    <v-divider class="mx-3 my-5"></v-divider>
    <v-btn
        v-for="i in circles"
        :key="i.title"
        :title="i.title"
        class="white text-center ml-1 mb-9"
        icon
        @click="action(i)"
    >
      <v-icon v-text="i.icon"/>
    </v-btn>
  </v-navigation-drawer>
</template>

<script>
import Profile from "@/components/Layout/Profile";

export default {
  name: "NavDrawer",
  components: {Profile},
  data: () => ({
    drawer: null,
    circles: [
      {
        title: "主控台",
        icon: "mdi-finance",
        path: "/manage"
      },
      {
        title: "系統設定",
        icon: "mdi-tune-vertical",
        path: "/setting"
      },
      {
        title: "關於系統",
        icon: "mdi-information-outline",
        path: "/about"
      },
    ],
  }),
  methods: {
    action(i) {
      if (i.path) {
        if (!this.$route.path.startsWith(i.path)) {
          this.$router.push(i.path)
        }
      } else if (i.url) {
        location.assign(i.url)
      } else {
        console.error(i)
      }
    }
  }
}
</script>
