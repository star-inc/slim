<template>
  <v-navigation-drawer v-model="drawer">
    <v-list class="pl-14" shaped>
      <v-list-item-group v-model="current">
        <v-list-item v-for="i in list" :key="i.title" @click="action(i)">
          <v-list-item-content>
            <v-list-item-title>{{ i.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "Drawer",
  data: () => ({
    drawer: null,
    current: 0,
    lists: {
      manage: [
        {
          title: "概觀",
          path: "/manage"
        },
        {
          title: "員工資料",
          path: "/manage/users"
        },
        {
          title: "產品資料",
          path: "/manage/products"
        },
        {
          title: "角色",
          path: "/manage/levels"
        },
        {
          title: "供應商",
          path: "/manage/providers"
        },
      ]
    }
  }),
  methods: {
    action(i) {
      if (i.path) {
        if (this.$route.path !== i.path) {
          this.$router.push(i.path)
        }
      } else if (i.url) {
        location.assign(i.url)
      } else {
        console.error(i)
      }
    }
  },
  computed: {
    list() {
      const path = this.$route.path.split("/")
      if (path.length < 2 || !(path[1] in this.lists)) return []
      return this.lists[path[1]]
    },
    currentRow() {
      return this.list.findIndex((i) => this.$route.path === i.path)
    }
  },
  watch: {
    currentRow(e) {
      this.current = e
    }
  }
}
</script>
