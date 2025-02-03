<template>
  <v-menu
      bottom
      min-width="200px"
      offset-y
      rounded
  >
    <template #activator="{ on }">
      <v-btn class="white text-center ml-1 my-5" icon v-on="on">
        <v-icon>mdi-account-outline</v-icon>
      </v-btn>
    </template>
    <v-card>
      <v-list-item-content class="justify-center">
        <div class="mx-auto text-center">
          <v-avatar color="white">
            <v-icon>mdi-account-outline</v-icon>
          </v-avatar>
          <h3>{{ $store.state.user.display_name }}</h3>
          <p class="text-caption mt-1">
            {{ $store.state.user.email }}
          </p>
          <v-divider class="my-3"/>
          <v-btn depressed rounded text>
            編輯個人檔案
          </v-btn>
          <v-divider class="my-3"/>
          <v-btn depressed link rounded text to="/logout">
            登出
          </v-btn>
        </div>
      </v-list-item-content>
    </v-card>
  </v-menu>
</template>

<script>
export default {
  name: "Profile"
}
</script>
