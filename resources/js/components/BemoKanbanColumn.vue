<template>
  <div class="kanban-column">
    <button
      type="button"
      class="btn-close"
      aria-label="Close"
      @click="deleteColumn"
    ></button>
    <h2>{{ column.title }}</h2>

    <draggable
      tag="ul"
      :options="{ group: 'ITEMS' }"
      @change="checkMove(column.id, $event)"
      :list="cards"
      @start="drag = true"
      @end="drag = false"
    >
      <bemo-kanban-card
        v-for="card in cards"
        :key="card.id"
        :card="card"
        @update="$emit('update')"
        @editCard="editCard"
      ></bemo-kanban-card>
    </draggable>

    <validation-observer
      class="add-card"
      v-slot="{ handleSubmit }"
      tag="div"
      ref="observer"
    >
      <validation-provider
        name="Title"
        :rules="{ required: true }"
        v-slot="validationContext"
      >
        <b-form-input
          type="text"
          class="form-control"
          placeholder="Card Title"
          v-model="newTitle"
          :state="getValidationState(validationContext)"
        >
        </b-form-input>
        <b-form-invalid-feedback id="input-1-live-feedback">{{
          validationContext.errors[0]
        }}</b-form-invalid-feedback>
      </validation-provider>
      <button class="btn btn-primary" @click="handleSubmit(addCard)">
        Add Card
      </button>
    </validation-observer>
  </div>
</template>
<script>
export default {
  props: {
    column: {
      required: true,
      type: Object,
    },
  },
  data() {
    return {
      newTitle: "",
    };
  },
  computed: {
    cards() {
      return this.column.cards || [];
    },
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
    deleteColumn() {
      if (confirm("Realy delete?")) {
        this.realDeleteColumn();
      }
    },
    async realDeleteColumn() {
      await axios.delete(`/api/${this.column.id}`);
      this.$emit("update");
    },
    async addCard() {
      await axios.post(`/api/card/${this.column.id}`, {
        title: this.newTitle,
      });
      this.$emit("update");
      this.newTitle = "";
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
    editCard(payload) {
      this.$emit("editCard", payload);
    },
    async checkMove(id, evt) {
      console.log(evt);
      if (evt.added) {
        const card = Object.assign({}, evt.added.element);
        card.column_id = id;
        card.order = evt.added.newIndex + 1;
        await axios.patch(`/api/card/${card.id}`, card);
      }
      if (evt.moved) {
        const newIndex = evt.moved.newIndex + 1;
        const oldIndex = evt.moved.oldIndex + 1;
        const card = Object.assign({}, evt.moved.element);
        card.order = newIndex;
        await axios.patch(`/api/card/${card.id}`, card);
        this.cards.forEach(async (v) => {
          if (parseInt(v.order) == newIndex) {
            const oldCard = Object.assign({}, v);
            oldCard.order = oldIndex;
            await axios.patch(`/api/card/${oldCard.id}`, oldCard);
          }
        });
      }
    },
  },
};
</script>
<style lang="scss">
.kanban-column {
  position: relative;
  padding-bottom: 120px;
  .btn-close {
    position: absolute;
    right: 0;
    font-size: 16px;
    top: 0;
  }
  .add-card {
    width: 200px;
    position: absolute;
    bottom: 0;
    left: 0;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
    padding: 10px;
    border-top: 1px lightblue solid;
  }
  ul {
    padding-left: 0;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
    li {
      list-style: none;
      padding: 10px;
      margin: 0 6px;
      display: block;
    }
  }
}
</style>