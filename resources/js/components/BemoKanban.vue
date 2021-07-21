<template>
  <div class="main">
    <bemo-kanban-column
      v-for="(column, i) in columns"
      :key="i"
      :column="column"
      @update="fetchData"
      @editCard="editCard"
    >
    </bemo-kanban-column>
    <validation-observer class="add-kanban" v-slot="{ handleSubmit }" tag="div" ref="observer">
      <validation-provider
        name="Title"
        :rules="{ required: true }"
        v-slot="validationContext"
      >
        <b-form-input
          type="text"
          class="form-control"
          placeholder="Column Title"
          v-model="newTitle"
          :state="getValidationState(validationContext)"
        >
        </b-form-input>
        <b-form-invalid-feedback id="input-1-live-feedback">{{
          validationContext.errors[0]
        }}</b-form-invalid-feedback>
      </validation-provider>
      <button class="btn btn-primary" @click="handleSubmit(addColumn)">
        Add column
      </button>
    </validation-observer>
    <b-modal v-model="modalShow" hide-footer hide-header v-if="modalShow">
        <bemo-kanban-card-edit :card="editCardModel" @close="closeModal" @update="fetchData"></bemo-kanban-card-edit>
    </b-modal>
    <button class="btn btn-warning export-btn" @click="exportDb">
        EXPORT DB
      </button>
  </div>
</template>
<script>
import BemoKanbanCard from './BemoKanbanCard.vue';
export default {
  components: { BemoKanbanCard },
  data() {
    return {
      columns: [],
      newTitle: "",
      modalShow: false,
    editCardModel: {}
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
    async fetchData() {
      const response = await axios.get("/api");
      this.columns = response.data.columns;
    },
    async addColumn() {
      await axios.post("/api", {
        title: this.newTitle,
      });
      this.fetchData();
      this.newTitle = ''
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
    editCard(card){
        this.editCardModel = Object.assign({}, card);
        this.modalShow = true
    },
    closeModal(){
        this.modalShow = false
    },
    async exportDb(){
        const response = await axios.post("/api/dump");
        if(response.data.status == 'OK'){
            window.open('/dump.sql', '_blank').focus();
        }
    }
  },
};
</script>
<style lang="scss">
.main {
  display: flex;
  gap: 10px;
  padding: 40px 60px;
    width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  .add-kanban,
  .kanban-column {
    width: 200px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
    border: 1px lightblue solid;
    & > h2 {
      font-size: 16px;
      text-align: center;
      line-height: 1.6;
      border-bottom: 1px lightblue solid;
    }
  }
  .add-kanban {
    padding: 10px;
    align-self: flex-start;
  }
  .export-btn{
      position: absolute;
      bottom: 0;
      right: 0;
  }
}
</style>