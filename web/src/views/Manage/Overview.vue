<template>
  <v-card>
    <v-container class="py-8 px-6" fluid>
      <v-row>
        <v-col v-for="(card, name) in cards" :key="name" cols="12">
          <v-card>
            <v-subheader>{{ name }}</v-subheader>
            <item-list :empty="card.length < 1" :loaded="card.loaded">
              <template #data>
                <v-list-item v-for="(item, index) in card.data" :key="index">
                  <v-list-item-content>
                    <v-list-item-title>
                      {{ item.title }}
                    </v-list-item-title>
                    <v-list-item-subtitle>
                      {{ item.description }}
                    </v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </template>
            </item-list>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>

<script>
import ItemList from "@/components/ItemList";

export default {
  name: "Overview",
  components: {ItemList},
  data: () => ({
    cards: {
      Overview: {
        loaded: true,
        data: [
          {
            title: "無法使用",
            description: "Unavailable",
          },
        ],
      },
      History: {
        loaded: false,
        data: [],
      },
    },
  }),
  created() {
    this.load();
  },
  methods: {
    load() {
      this.cards.History.loaded = false;
      this.$axios.get("/history/all").then((resp) => {
        this.cards.History.loaded = true;
        this.cards.History.data = resp.data.map((item) => ({
          title: Date(item.created_time * 1000).toLocaleString(),
          description: `User ${
              item.user
          } requested to ${item.method.toLowerCase()} ${item.resource}`,
        }));
      });
    },
  },
};
</script>
