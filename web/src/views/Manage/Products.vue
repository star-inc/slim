<template>
  <v-card>
    <v-container class="py-8 px-6" fluid>
      <v-card>
        <v-card-title>
          產品資料
        </v-card-title>
        <item-list :empty="dataset.length < 1" :loaded="loaded">
          <template #data>
            <v-list-item v-for="(item, index) in exportData" :key="index">
              <v-list-item-content>
                <v-list-item-title>
                  {{ item.display_name }}
                </v-list-item-title>
                <v-list-item-subtitle>
                  成本：{{ item.cost_value }} /
                  售價：{{ item.sell_value }} /
                  數量：{{ item.remain_amount }}
                </v-list-item-subtitle>
              </v-list-item-content>
              <v-list-item-action v-show="mode===2">
                <v-btn class="amber darken-1 white--text" depressed rounded @click="modify(item)">
                  <v-icon>mdi-pencil-outline</v-icon>
                </v-btn>
              </v-list-item-action>
              <v-list-item-action v-show="mode===2">
                <v-btn class="red darken-1 white--text" depressed rounded @click="remove(item)">
                  <v-icon>mdi-trash-can-outline</v-icon>
                </v-btn>
              </v-list-item-action>
            </v-list-item>
          </template>
        </item-list>
      </v-card>
    </v-container>
    <modify-drawer @append="append" @modify="mode=2" @view="mode=0"/>
    <v-overlay :value="mode === 1 || mode === 3 || mode === 4">
      <v-container>
        <v-card v-if="mode === 1" light>
          <v-card-title>新增產品資料</v-card-title>
          <v-card-subtitle>Append Product</v-card-subtitle>
          <v-card-subtitle class="red white--text" v-show="editing.message" v-text="editing.message"/>
          <v-card-text>
            <v-form>
              <v-text-field v-model="editing.target.display_name" label="名稱" type="name"/>
              <v-text-field v-model="editing.target.cost_value" label="成本" type="text"/>
              <v-text-field v-model="editing.target.sell_value" label="售價" type="text"/>
              <v-text-field v-model="editing.target.remain_amount" label="數量" type="text"/>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn :disabled="editing.loading" depressed @click="mode = 0">取消</v-btn>
            <v-spacer/>
            <v-btn :loading="editing.loading" class="secondary" @click="appendSubmit" depressed>送出</v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-if="mode === 3" light>
          <v-card-title>編輯產品資料</v-card-title>
          <v-card-subtitle>Modify Product</v-card-subtitle>
          <v-card-subtitle class="red white--text" v-show="editing.message" v-text="editing.message"/>
          <v-card-text>
            <v-form>
              <v-text-field v-model="editing.target.display_name" label="名稱" type="name"/>
              <v-text-field v-model="editing.target.cost_value" label="成本" type="text"/>
              <v-text-field v-model="editing.target.sell_value" label="售價" type="text"/>
              <v-text-field v-model="editing.target.remain_amount" label="數量" type="text"/>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn :disabled="editing.loading" depressed @click="mode = 0">取消</v-btn>
            <v-spacer/>
            <v-btn :loading="editing.loading" class="secondary" @click="modifySubmit" depressed>送出</v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-if="mode === 4" light>
          <v-card-title>刪除產品資料</v-card-title>
          <v-card-subtitle>Delete Product</v-card-subtitle>
          <v-card-subtitle class="red white--text" v-show="editing.message" v-text="editing.message"/>
          <v-card-text>
            {{ editing.target.display_name }} 的資料將被刪除
          </v-card-text>
          <v-card-actions>
            <v-btn :disabled="editing.loading" depressed @click="mode = 0">取消</v-btn>
            <v-spacer/>
            <v-btn :loading="editing.loading" class="secondary" @click="removeSubmit" depressed>送出</v-btn>
          </v-card-actions>
        </v-card>
      </v-container>
    </v-overlay>
  </v-card>
</template>

<script>
import capitalize from "capitalize";

import ItemList from "@/components/ItemList";
import ModifyDrawer from "@/components/ModifyDrawer";

export default {
  name: "Products",
  components: {ModifyDrawer, ItemList},
  data: () => ({
    loaded: false,
    mode: 0,
    editing: {
      message: null,
      loading: false,
      target: null,
    },
    dataset: []
  }),
  created() {
    this.load()
  },
  methods: {
    load() {
      this.loaded = false
      this.$axios.get("/product/all").then(resp => {
        this.loaded = true;
        this.dataset = resp.data
      })
    },
    append() {
      this.editing.target = {}
      this.mode = 1
    },
    modify(item) {
      this.editing.target = {}
      Object.assign(this.editing.target, item)
      this.mode = 3
    },
    remove(item) {
      this.editing.target = item
      this.mode = 4
    },
    getParams() {
      const form = new URLSearchParams();
      form.set("display_name", this.editing.target.display_name);
      form.set("cost_value", this.editing.target.cost_value);
      form.set("sell_value", this.editing.target.sell_value);
      form.set("remain_amount", this.editing.target.remain_amount);
      return form
    },
    async appendSubmit() {
      this.editing.loading = true;
      try {
        const form = this.getParams()
        const response = await this.$axios.post("/product", form)
        if (response.status === 201) {
          this.mode = 0
          this.load()
        }
      } catch (e) {
        this.editing.message = e.response.data.message
          ? capitalize(e.response.data.message)
          : "Failed"
        console.warn(e)
      }
      this.editing.loading = false;
    },
    async modifySubmit() {
      this.editing.loading = true;
      try {
        const form = this.getParams()
        form.set("uuid", this.editing.target.uuid)
        const response = await this.$axios.put("/product", form)
        if (response.status === 204) {
          this.mode = 0
          this.load()
        }
      } catch (e) {
        this.editing.message = e.response.data.message
          ? capitalize(e.response.data.message)
          : "Failed"
        console.warn(e)
      }
      this.editing.loading = false;
    },
    async removeSubmit() {
      this.editing.loading = true;
      try {
        const response = await this.$axios.delete("/product", {
          params: {uuid: this.editing.target.uuid}
        })
        if (response.status === 204) {
          this.mode = 0
          this.load()
        }
      } catch (e) {
        this.editing.message = e.response.data.message
          ? capitalize(e.response.data.message)
          : "Failed"
        console.warn(e)
      }
      this.editing.loading = false;
    },
  },
  computed: {
    exportData() {
      return this.dataset
    }
  }
}
</script>
