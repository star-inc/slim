<template>
  <div>
    <v-card v-if="!loaded" flat>
      <v-card-text>
        <v-progress-circular color="primary" indeterminate/>
      </v-card-text>
    </v-card>
    <v-list v-else>
      <v-list-item v-if="empty">
        <v-list-item-content>
          <v-list-item-title>空白</v-list-item-title>
          <v-list-item-subtitle>空無一物</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
      <slot name="data"/>
    </v-list>
  </div>
</template>

<script>
export default {
  name: "ItemList",
  props: {
    loaded: {
      type: Boolean,
      required: true
    },
    empty: {
      type: Boolean,
      required: true
    }
  }
}
</script>
