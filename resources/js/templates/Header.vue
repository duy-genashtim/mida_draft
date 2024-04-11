<!-- Header.vue -->
<template>

</template>
  
  <script>
  export default {
    // Component logic goes here
  }
  </script>
  
  <style scoped>
  .multisteps-form__progress-btn:after {
        display: none;
    }
    .wizard-part-title {
        padding: 70px;
        padding-top: 60px;
        padding-bottom: 10px;
    }
    .wizard-topper {
        top: -340px;
        display: none;
    }
    .wizard-content-item {
        padding-top: 25px;
        margin-top: -40px;
    }
    .multisteps-form__progress {
        padding-top: 40px;
        padding-bottom: 120px;
        display: flex;
        align-items: center;
        justify-content: space-around;
    }
    .wizard-topper .wizard-progress .progress .progress-bar {
        height: 19px;
    }
    .pb-10 {
        padding-bottom: 10px;
    }
    .n-checked label span {
        cursor: pointer;
    }
    .n-checked label {
        margin-bottom: .25rem;
    }
    .n-checked label span,
    .multisteps-form__progress-btn.js-active:before,
    .multisteps-form__progress-btn.js-active {
        color: #5756a2;
        border-color: #5756a2;
    }
    .radio-wrapper {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }
    .has-float-label i {
        z-index: 999;
    }
    .has-select-info select {
        text-indent: 3rem;
    }

    @media screen and (max-width: 767px) {

        .wizard-topper {
            width: 70%;
            top: -150px !important;
        }
        
    }

    @media screen and (max-width: 460px) {
        .multisteps-form__progress {
            padding-bottom: 30px;
            padding-top: 20px;
        }
        .wizard-part-title h3 {
            font-size: 36px;
            text-align: center;
        }
    }
  </style>