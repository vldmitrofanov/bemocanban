<template>
  <div class="card-edit">
    <button
      type="button"
      class="btn-close"
      aria-label="Close"
      @click="close"
    ></button>
    <validation-observer
      class="edit"
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
          placeholder="Column Title"
          v-model="card.title"
          :state="getValidationState(validationContext)"
        >
        </b-form-input>
        <b-form-invalid-feedback id="input-1-live-feedback">{{
          validationContext.errors[0]
        }}</b-form-invalid-feedback>
      </validation-provider>

      <b-form-textarea
        type="text"
        class="form-control"
        placeholder="Card Description"
        v-model="card.description"
      />
      <button class="btn btn-primary" @click="handleSubmit(editCard)">
        Save
      </button>
    </validation-observer>
  </div>
</template>
<script>
export default {
  props: {
    card: {
      required: true,
      type: Object,
    },
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
    async editCard() {
      await axios.patch(`/api/card/${this.card.id}`, this.card);
      this.$emit("update");
      this.$emit("close");
    },
    close() {
      this.$emit("close");
    },
  },
};
</script>
<style lang="scss">
.card-edit {
  padding: 36px 10px 16px;
  .edit {
    padding-left: 0;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
  }
  .btn-close{
    position: absolute;
    top: 10px;
    right: 12px;
  }
}
</style>