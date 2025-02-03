<template>
  <v-navigation-drawer app clipped right>
    <v-list>
      <v-list-item-group v-model="current">
        <v-list-item @click="$emit('view')">
          <v-list-item-content>
            <v-list-item-title>檢視</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$emit('modify')">
          <v-list-item-content>
            <v-list-item-title>編輯</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
      <v-list-item @click="$emit('append')">
        <v-list-item-content>
          <v-list-item-title>新增</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item @click="$emit('import')">
        <v-list-item-content>
          <v-list-item-title>匯入</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item @click="$emit('export')">
        <v-list-item-content>
          <v-list-item-title>匯出</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "ModifyDrawer",
  data: () => ({
    current: 0
  })
}
</script>
