<script>
import axios from "axios";
import { ref } from "vue";
import Header from "../templates/Header.vue";
import { object, string } from "yup"; //used for custom validation
import $ from "jQuery"; //only imported because the form template requires it
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const response = ref();
const personalDetails = ref([]);
const hasFormError = ref(false);
const fiscal_year = ref();
const subIndustryItems = ref([]);
//update schema to validate necessary fields
const personalDetailSchema = object().shape({
	firstName: string().required(),
	lastName: string().required(),
	email: string().required().email()
});

const companyInfoFormSchema = object().shape({
	companyName: string().required(),
	companyLocation: string().required()
});

export default {

	mounted() {
	
		const script = document.createElement("script");
		script.onload = this.onMainLoaded;
		script.type = "text/javascript";
		script.src = "/js/main.js";
		
		document.head.appendChild(script);

		this.fetchGeneralIndustryItems();
	},

	data() {
		return {
			isLoading: false,
			hasFormError: this.hasFormError,
			hasStep1Submitted: false,
			hasStep2Submitted: false,
			generalIndustryItems: [],
			subIndustryItems: [],
			values: {
				step1: {
					email: "",
					firstName: "",
					lastName: ""
				},
				step2: {
					companyName: "",
					companyLocation: "",
					lastFiscalYearEndDate: null,
					country: ""
				},
				step3: {
					workerSize: "",
					generalIndustry: "",
					subIndustry: ""
				}
			},
			errors: {
				email: "",
				firstName: "",
				lastName: "",
				companyName: "",
				companyLocation: "",
				lastFiscalYearEndDate: "",
				country: "",
				workerSize: "",
				generalIndustry: "",
				subIndustry: ""
			},
			fiscal_year,
		}
	},

	components: {
		Header,
		Loading,
		VueDatePicker
	},

	methods: {
		onSubmit(e) {
			this.isLoading = true;

          	axios.post('/api/register', {
				appointment_details : this.values.step1, 
					company_details : this.values.step2,
					industry_details: this.values.step3}).then(response => {

						this.$router.push('/thank-you');
			});
		},
		//this was ported from the html form template and utilized the jquery library
		onMainLoaded() {
			$('.wizard > .steps li a').click(function() {
				$(this).parent().addClass('checked');
				$(this).parent().prevAll().addClass('checked');
				$(this).parent().nextAll().removeClass('checked');
			});	

			$('.wizard > .steps li a').click(function() {
				$(this).parent().addClass('checked');
				$(this).parent().prevAll().addClass('checked');
				$(this).parent().nextAll().removeClass('checked');
			});
			$(":checkbox").on('click', function(){
				$(this).parent().parent().toggleClass("checked");
			});
			// Custome Jquery Step Button
			$('.forward').click(function() {
				$("#wizard").steps('next');
			})
			$('.backward').click(function() {
				$("#wizard").steps('previous');
			})
			// Select Dropdown
			$('html').click(function() {
				$('.select .dropdown').hide();
			});
			$('.select').click(function(event) {
				event.stopPropagation();
			});
			$('.select .select-control').click(function() {
				$(this).parent().next().toggle();
			})
			$('.select .dropdown li').click(function() {
				$(this).parent().toggle();
				var text = $(this).attr('rel');
				$(this).parent().prev().find('div').text(text);
			})
			$(".wizard-option-list ul li ").click(function() {
				$(".wizard-option-list ul li").removeClass("active");
				$(this).addClass("active");
			});
			$(".texable-option ").click(function() {
				$(".texable-option").removeClass("active");
				$(this).addClass("active");
			});
			updateContainer();
			$(window).resize(function() {
				updateContainer();
			});
		},

		validate(field, currentStep) {

			let values = {};

			if (currentStep == 1) {
				values = this.values.step1;

				personalDetailSchema
				.validateAt(field, values)
				.then(() => {
					this.errors[field] = "";
				})
				.catch(err => {

					if ((this.hasStep1Submitted)) {
						this.errors[field] = err.message;	
					}

					console.log(err);
				});
			}

			if (currentStep == 2) {
				values = this.values.step2;

				companyInfoFormSchema
				.validateAt(field, values)
				.then(() => {
					this.errors[field] = "";
				})
				.catch(err => {

					if ((this.hasStep2Submitted)) {
						this.errors[field] = err.message;	
					}

					console.log(err);
				});
			}
		},

		validateStep1() {
			this.hasStep1Submitted = true;

			personalDetailSchema
			.validate(this.values.step1, { abortEarly: false })
			.then(() => {
				this.errors = {};
				this.hasFormError = false;
			})
			.catch(err => {
				err.inner.forEach(error => {
					this.errors[error.path] = error.message;
				});

				this.hasFormError = true;
			});

			return false;
		},

		validateStep2() {
			this.hasStep2Submitted = true;

			companyInfoFormSchema
			.validate(this.values.step2, { abortEarly: false })
			.then(() => {
				this.errors = {};
				this.hasFormError = false;
			})
			.catch(err => {
				err.inner.forEach(error => {
					this.errors[error.path] = error.message;
				});

				this.hasFormError = true;
			});

			return false;
		},

		fetchGeneralIndustryItems() {
			axios.get('/api/fetchGeneralIndustry')
			.then(response => {
				console.log( response.data);
				this.generalIndustryItems = response.data;
			})
			.catch(error => {
				console.error('Error fetching items:', error);
			});
		},
		// duy added
		handleDate(modelData){
			// date.value = modelData;
			const month = modelData.month + 1;
  			const year = modelData.year;
			this.values.step2.lastFiscalYearEndDate = `${year}-${month}`;
			fiscal_year.value  = modelData;
			console.log(modelData);
			console.log(this.values);
			// do something else with the data
		},
		fetchSubIndustryItems() {
			console.log(this.values);
			axios
			.get(`/api/fetchSubIndustry/${this.values.step3.generalIndustry}`)
			.then((response) => {
				console.log( response.data);
				this.subIndustryItems = response.data;
			})
			.catch((error) => {
			console.log(error);
			});
		}
	}
}
</script>
<template>

	<div class="vl-parent">
        <Loading v-model:active="isLoading"
                 :can-cancel="false"
                 loader="dots"
				 is-full-page="true"
				 color="#5756a2"
				 width="80"
				 height="80" />
	</div>

    <Header />

    <div class="clearfix"></div>

	<div class="wrapper clearfix">
		<div class="wizard-part-title">
			<h3>MIDA Registration</h3>
		</div>
		<!--multisteps-form-->
		<div class="multisteps-form">
			<!--progress bar-->
			<div class="row">
				<div class="col-12 col-lg-12 ml-auto mr-auto mb-5 mt-1">
					<div class="multisteps-form__progress">
						<button class="multisteps-form__progress-btn js-active">Personal details</button>
						<button class="multisteps-form__progress-btn">Company Information</button>
						<button class="multisteps-form__progress-btn">Industry</button>
					</div>
				</div>
			</div>
			<!--form panels-->
			<div class="row">
				<div class="col-12 col-lg-12 m-auto">
					<form class="multisteps-form__form clearfix" :class="{ 'form-invalid': hasFormError }" v-on:submit.prevent="onSubmit" action="#" method="post" id="wizard">
						
						<!--single form panel-->
						<div class="multisteps-form__panel js-active" data-animation="slideVert">
							<div class="inner ">
								<div class="wizard-topper">
									<div class="wizard-progress">
										<span>1 of 3 Completed</span>
										<div class="progress">
											<div class="progress-bar" style="width: 33%;">
											</div>
										</div>
									</div>
								</div>
								<div class="wizard-content-item text-center">
									<h2>Personal details</h2>
									<p>The following are required fields for the<br> B Impact Assessment</p>
								</div>
								<div class="wizard-content-form">
									<div class="wizard-form-field">
										<div class="row">
											<div class="col-md-6">
												<div class="wizard-form-input position-relative form-group has-float-label">
													<input type="text" class="form-control" :class="{ 'is-invalid': !!errors.firstName }" name="first_name" v-model="values.step1.firstName" placeholder="First Name" required @blur="validate('firstName', 1)" @keyup="validate('firstName', 1)">
													<label>First Name</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="wizard-form-input position-relative form-group has-float-label">
													<input type="text" class="form-control" :class="{ 'is-invalid': !!errors.lastName }" name="last_name" v-model="values.step1.lastName" placeholder="Last Name" required @blur="validate('lastName', 1)" @keyup="validate('lastName', 1)">
													<label>Last Name</label>
												</div>
											</div>
										</div>
										<div class="wizard-form-input position-relative form-group has-float-label">
											<input type="email" class="form-control" :class="{ 'is-invalid': !!errors.email }" name="email" v-model="values.step1.email" placeholder="Email Address" required @blur="validate('email', 1)" @keyup="validate('email', 1)">
											<label>Email Address</label>
										</div>
									</div>
								</div>
								<div class="wizard-footer">
									<div class="wizard-imgbg">
										<img src="../../img/v3.png" alt="">
									</div>
									<div class="actions">
										<ul>
											<li><span class="js-btn-next" title="NEXT" @click="validateStep1">NEXT <i class="fa fa-arrow-right"></i></span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!--single form panel-->
						<div class="multisteps-form__panel" data-animation="slideVert">
							<div class="inner">
								<div class="wizard-topper">
									<div class="wizard-progress">
										<span>2 of 3 Completed</span>
										<div class="progress">
											<div class="progress-bar" style="width: 66%;"></div>
										</div>
									</div>
								</div>
								<div class="wizard-content-item text-center">
									<h2>Company Information</h2>
									<p>Tell us about your company</p>
								</div>

								<div class="wizard-content-form">
									<div class="wizard-form-field">
										<div class="wizard-form-input position-relative form-group has-float-label">
											<i data-toggle="tooltip" data-placement="bottom" title="If you want your invoice address to a compnay. Leave blank to use full name" class="fa fa-info"></i>
											<input type="text" class="form-control" :class="{ 'is-invalid': !!errors.companyName }" name="company_name" v-model="values.step2.companyName" placeholder="Company Name" required @blur="validate('companyName', 2)" @keyup="validate('companyName', 2)">
											<label>Company Name</label>
										</div>
										<div class="wizard-form-input position-relative form-group has-float-label">
											<input type="text" class="form-control" :class="{ 'is-invalid': !!errors.companyLocation }" name="company_location" v-model="values.step2.companyLocation" placeholder="Location" 
											required @blur="validate('companyLocation', 2)" @keyup="validate('companyLocation', 2)">
											<label>Location</label>
										</div>
										<div class="row">
											<div class="col-md-8">
												<div class="wizard-form-input position-relative form-group has-float-label">
													<i data-toggle="tooltip" data-placement="bottom" title="Testin tooltip info here" class="fa fa-info"></i>
													<!-- <input type="text" class="form-control" name="fiscal_year_end_date" v-model="values.step2.lastFiscalYearEndDate" placeholder="Last Fiscal Year End Date"> -->
													<VueDatePicker class="" name="fiscal_year_end_date" v-model="fiscal_year" placeholder=""   @update:model-value="handleDate"
													input-class-name="dp-custom-input" month-picker arrow-navigation ></VueDatePicker>
													<label>Last Fiscal Year End Date</label>
													
												</div>
											</div>
											<div class="col-md-4">
												<div class="wizard-form-input position-relative form-group has-float-label mt-0 n-select-option">
													<select id="country" name="country" v-model="values.step2.country" class="form-control">
														<option value="">Country</option>
														<option value="Afghanistan">Afghanistan</option>
														<option value="Åland Islands">Åland Islands</option>
														<option value="Albania">Albania</option>
														<option value="Algeria">Algeria</option>
														<option value="American Samoa">American Samoa</option>
														<option value="Andorra">Andorra</option>
														<option value="Angola">Angola</option>
														<option value="Anguilla">Anguilla</option>
														<option value="Antarctica">Antarctica</option>
														<option value="Antigua and Barbuda">Antigua and Barbuda</option>
														<option value="Argentina">Argentina</option>
														<option value="Armenia">Armenia</option>
														<option value="Aruba">Aruba</option>
														<option value="Australia">Australia</option>
														<option value="Austria">Austria</option>
														<option value="Azerbaijan">Azerbaijan</option>
														<option value="Bahamas">Bahamas</option>
														<option value="Bahrain">Bahrain</option>
														<option value="Bangladesh">Bangladesh</option>
														<option value="Barbados">Barbados</option>
														<option value="Belarus">Belarus</option>
														<option value="Belgium">Belgium</option>
														<option value="Belize">Belize</option>
														<option value="Benin">Benin</option>
														<option value="Bermuda">Bermuda</option>
														<option value="Bhutan">Bhutan</option>
														<option value="Bolivia">Bolivia</option>
														<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
														<option value="Botswana">Botswana</option>
														<option value="Bouvet Island">Bouvet Island</option>
														<option value="Brazil">Brazil</option>
														<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
														<option value="Brunei Darussalam">Brunei Darussalam</option>
														<option value="Bulgaria">Bulgaria</option>
														<option value="Burkina Faso">Burkina Faso</option>
														<option value="Burundi">Burundi</option>
														<option value="Cambodia">Cambodia</option>
														<option value="Cameroon">Cameroon</option>
														<option value="Canada">Canada</option>
														<option value="Cape Verde">Cape Verde</option>
														<option value="Cayman Islands">Cayman Islands</option>
														<option value="Central African Republic">Central African Republic</option>
														<option value="Chad">Chad</option>
														<option value="Chile">Chile</option>
														<option value="China">China</option>
														<option value="Christmas Island">Christmas Island</option>
														<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
														<option value="Colombia">Colombia</option>
														<option value="Comoros">Comoros</option>
														<option value="Congo">Congo</option>
														<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
														<option value="Cook Islands">Cook Islands</option>
														<option value="Costa Rica">Costa Rica</option>
														<option value="Cote D'ivoire">Cote D'ivoire</option>
														<option value="Croatia">Croatia</option>
														<option value="Cuba">Cuba</option>
														<option value="Cyprus">Cyprus</option>
														<option value="Czech Republic">Czech Republic</option>
														<option value="Denmark">Denmark</option>
														<option value="Djibouti">Djibouti</option>
														<option value="Dominica">Dominica</option>
														<option value="Dominican Republic">Dominican Republic</option>
														<option value="Ecuador">Ecuador</option>
														<option value="Egypt">Egypt</option>
														<option value="El Salvador">El Salvador</option>
														<option value="Equatorial Guinea">Equatorial Guinea</option>
														<option value="Eritrea">Eritrea</option>
														<option value="Estonia">Estonia</option>
														<option value="Ethiopia">Ethiopia</option>
														<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
														<option value="Faroe Islands">Faroe Islands</option>
														<option value="Fiji">Fiji</option>
														<option value="Finland">Finland</option>
														<option value="France">France</option>
														<option value="French Guiana">French Guiana</option>
														<option value="French Polynesia">French Polynesia</option>
														<option value="French Southern Territories">French Southern Territories</option>
														<option value="Gabon">Gabon</option>
														<option value="Gambia">Gambia</option>
														<option value="Georgia">Georgia</option>
														<option value="Germany">Germany</option>
														<option value="Ghana">Ghana</option>
														<option value="Gibraltar">Gibraltar</option>
														<option value="Greece">Greece</option>
														<option value="Greenland">Greenland</option>
														<option value="Grenada">Grenada</option>
														<option value="Guadeloupe">Guadeloupe</option>
														<option value="Guam">Guam</option>
														<option value="Guatemala">Guatemala</option>
														<option value="Guernsey">Guernsey</option>
														<option value="Guinea">Guinea</option>
														<option value="Guinea-bissau">Guinea-bissau</option>
														<option value="Guyana">Guyana</option>
														<option value="Haiti">Haiti</option>
														<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
														<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
														<option value="Honduras">Honduras</option>
														<option value="Hong Kong">Hong Kong</option>
														<option value="Hungary">Hungary</option>
														<option value="Iceland">Iceland</option>
														<option value="India">India</option>
														<option value="Indonesia">Indonesia</option>
														<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
														<option value="Iraq">Iraq</option>
														<option value="Ireland">Ireland</option>
														<option value="Isle of Man">Isle of Man</option>
														<option value="Israel">Israel</option>
														<option value="Italy">Italy</option>
														<option value="Jamaica">Jamaica</option>
														<option value="Japan">Japan</option>
														<option value="Jersey">Jersey</option>
														<option value="Jordan">Jordan</option>
														<option value="Kazakhstan">Kazakhstan</option>
														<option value="Kenya">Kenya</option>
														<option value="Kiribati">Kiribati</option>
														<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
														<option value="Korea, Republic of">Korea, Republic of</option>
														<option value="Kuwait">Kuwait</option>
														<option value="Kyrgyzstan">Kyrgyzstan</option>
														<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
														<option value="Latvia">Latvia</option>
														<option value="Lebanon">Lebanon</option>
														<option value="Lesotho">Lesotho</option>
														<option value="Liberia">Liberia</option>
														<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
														<option value="Liechtenstein">Liechtenstein</option>
														<option value="Lithuania">Lithuania</option>
														<option value="Luxembourg">Luxembourg</option>
														<option value="Macao">Macao</option>
														<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
														<option value="Madagascar">Madagascar</option>
														<option value="Malawi">Malawi</option>
														<option value="Malaysia">Malaysia</option>
														<option value="Maldives">Maldives</option>
														<option value="Mali">Mali</option>
														<option value="Malta">Malta</option>
														<option value="Marshall Islands">Marshall Islands</option>
														<option value="Martinique">Martinique</option>
														<option value="Mauritania">Mauritania</option>
														<option value="Mauritius">Mauritius</option>
														<option value="Mayotte">Mayotte</option>
														<option value="Mexico">Mexico</option>
														<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
														<option value="Moldova, Republic of">Moldova, Republic of</option>
														<option value="Monaco">Monaco</option>
														<option value="Mongolia">Mongolia</option>
														<option value="Montenegro">Montenegro</option>
														<option value="Montserrat">Montserrat</option>
														<option value="Morocco">Morocco</option>
														<option value="Mozambique">Mozambique</option>
														<option value="Myanmar">Myanmar</option>
														<option value="Namibia">Namibia</option>
														<option value="Nauru">Nauru</option>
														<option value="Nepal">Nepal</option>
														<option value="Netherlands">Netherlands</option>
														<option value="Netherlands Antilles">Netherlands Antilles</option>
														<option value="New Caledonia">New Caledonia</option>
														<option value="New Zealand">New Zealand</option>
														<option value="Nicaragua">Nicaragua</option>
														<option value="Niger">Niger</option>
														<option value="Nigeria">Nigeria</option>
														<option value="Niue">Niue</option>
														<option value="Norfolk Island">Norfolk Island</option>
														<option value="Northern Mariana Islands">Northern Mariana Islands</option>
														<option value="Norway">Norway</option>
														<option value="Oman">Oman</option>
														<option value="Pakistan">Pakistan</option>
														<option value="Palau">Palau</option>
														<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
														<option value="Panama">Panama</option>
														<option value="Papua New Guinea">Papua New Guinea</option>
														<option value="Paraguay">Paraguay</option>
														<option value="Peru">Peru</option>
														<option value="Philippines">Philippines</option>
														<option value="Pitcairn">Pitcairn</option>
														<option value="Poland">Poland</option>
														<option value="Portugal">Portugal</option>
														<option value="Puerto Rico">Puerto Rico</option>
														<option value="Qatar">Qatar</option>
														<option value="Reunion">Reunion</option>
														<option value="Romania">Romania</option>
														<option value="Russian Federation">Russian Federation</option>
														<option value="Rwanda">Rwanda</option>
														<option value="Saint Helena">Saint Helena</option>
														<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
														<option value="Saint Lucia">Saint Lucia</option>
														<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
														<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
														<option value="Samoa">Samoa</option>
														<option value="San Marino">San Marino</option>
														<option value="Sao Tome and Principe">Sao Tome and Principe</option>
														<option value="Saudi Arabia">Saudi Arabia</option>
														<option value="Senegal">Senegal</option>
														<option value="Serbia">Serbia</option>
														<option value="Seychelles">Seychelles</option>
														<option value="Sierra Leone">Sierra Leone</option>
														<option value="Singapore">Singapore</option>
														<option value="Slovakia">Slovakia</option>
														<option value="Slovenia">Slovenia</option>
														<option value="Solomon Islands">Solomon Islands</option>
														<option value="Somalia">Somalia</option>
														<option value="South Africa">South Africa</option>
														<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
														<option value="Spain">Spain</option>
														<option value="Sri Lanka">Sri Lanka</option>
														<option value="Sudan">Sudan</option>
														<option value="Suriname">Suriname</option>
														<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
														<option value="Swaziland">Swaziland</option>
														<option value="Sweden">Sweden</option>
														<option value="Switzerland">Switzerland</option>
														<option value="Syrian Arab Republic">Syrian Arab Republic</option>
														<option value="Taiwan, Province of China">Taiwan, Province of China</option>
														<option value="Tajikistan">Tajikistan</option>
														<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
														<option value="Thailand">Thailand</option>
														<option value="Timor-leste">Timor-leste</option>
														<option value="Togo">Togo</option>
														<option value="Tokelau">Tokelau</option>
														<option value="Tonga">Tonga</option>
														<option value="Trinidad and Tobago">Trinidad and Tobago</option>
														<option value="Tunisia">Tunisia</option>
														<option value="Turkey">Turkey</option>
														<option value="Turkmenistan">Turkmenistan</option>
														<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
														<option value="Tuvalu">Tuvalu</option>
														<option value="Uganda">Uganda</option>
														<option value="Ukraine">Ukraine</option>
														<option value="United Arab Emirates">United Arab Emirates</option>
														<option value="United Kingdom">United Kingdom</option>
														<option value="United States">United States</option>
														<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
														<option value="Uruguay">Uruguay</option>
														<option value="Uzbekistan">Uzbekistan</option>
														<option value="Vanuatu">Vanuatu</option>
														<option value="Venezuela">Venezuela</option>
														<option value="Viet Nam">Viet Nam</option>
														<option value="Virgin Islands, British">Virgin Islands, British</option>
														<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
														<option value="Wallis and Futuna">Wallis and Futuna</option>
														<option value="Western Sahara">Western Sahara</option>
														<option value="Yemen">Yemen</option>
														<option value="Zambia">Zambia</option>
														<option value="Zimbabwe">Zimbabwe</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									
								</div>

								<div class="wizard-footer">
									<div class="wizard-imgbg">
										<img src="../../img/v2.png" alt="">
									</div>
									<div class="actions">
										<ul>
											<li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK</span></li>
											<li><span class="js-btn-next" title="NEXT" @click="validateStep2">NEXT <i class="fa fa-arrow-right"></i></span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!--single form panel-->
						<div class="multisteps-form__panel" data-animation="slideVert">
							<div class="inner pb-10">
								<div class="wizard-topper">
									<div class="wizard-progress">
										<span>3 of 3 Completed</span>
										<div class="progress">
											<div class="progress-bar" style="width: 99%;"></div>
										</div>
									</div>
								</div>
								<div class="wizard-content-item text-center">
									<h2>Industry</h2>
									<p>Industry and workers' size</p>
								</div>
								<div class="wizard-identity wizard-content-form">
									<div class="row">
										<div class="col-md-6">
											<div class="n-checked pb-3" style="height: auto;">
												<span class="mb-3">Workers' Size</span>
												<div class="radio-wrapper">
												<label>
													<input type="radio" name="size" v-model="values.step3.workerSize" value="0" checked>
													<span class="checkmark">0</span>
												</label>
												<label>
													<input type="radio" name="size" v-model="values.step3.workerSize" value="1-9">
													<span class="checkmark">1 to 9</span>
												</label>
												<label>
													<input type="radio" name="size" v-model="values.step3.workerSize" value="10-49">
													<span class="checkmark">10 to 49</span>
												</label>
												<label>
													<input type="radio" name="size" v-model="values.step3.workerSize" value="50-249">
													<span class="checkmark">50 to 249</span>
												</label>
												<label>
													<input type="radio" name="size" v-model="values.step3.workerSize" value="250-999">
													<span class="checkmark">250 to 999</span>
												</label>
												<label>
													<input type="radio" name="size" v-model="values.step3.workerSize" value="1000+">
													<span class="checkmark">1000+</span>
												</label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="wizard-identity-box text-center">
												<div class="wizard-identity-icon">
													<img src="../../img/vi1.png" alt="">
												</div>
											</div>
											
										</div>
									</div>
									<div class="wizard-form-field">
										<div class="row">
											<div class="col-md-12">
												<div class="wizard-form-input position-relative form-group has-float-label has-select-info">
													<i data-toggle="tooltip" data-placement="bottom" title="Please select general industry. More details of selected industry will show here." class="fa fa-info"></i>
													<div class="n-select-option">
														<select style="margin-bottom:.65rem" v-model="values.step3.generalIndustry" @change="fetchSubIndustryItems()">
															<option v-for="item in generalIndustryItems" :key="item.general_industry_id" :value="item.general_industry_id">{{ item.name }}</option>
														</select>
													</div>
												</div>
												<div class="wizard-form-input position-relative form-group has-float-label has-select-info">
													<i data-toggle="tooltip" data-placement="bottom" title="Please select Sub industry. More details of selected industry will show here." class="fa fa-info"></i>
													<div class="n-select-option">
														<select style="margin-bottom:.65rem"   v-model="values.step3.subIndustry">
															<option v-for="item in subIndustryItems" :key="item.sub_industry_id" :value="item.sub_industry_id">{{ item.name }}</option>
														</select>
													</div>
												</div>
												<!-- <div class="wizard-form-input position-relative form-group has-float-label">
													<i data-toggle="tooltip" data-placement="bottom" title="Make this tooltip dynamic for subindustry" class="fa fa-info"></i>
													<input type="text" class="form-control" name="sub_industry" v-model="values.step3.subIndustry" placeholder="Sub Industries">
													<label>Sub Industries</label>
												</div> -->
											</div>
										</div>
									</div>
								</div>
								<div class="wizard-footer">
									<div class="wizard-imgbg">
										<img src="../../img/v3.png" alt="">
									</div>
									<div class="actions">
										<ul>
											<li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK</span></li>
											<li><button type="submit" id="submit-form" title="NEXT">SUBMIT <i class="fa fa-arrow-right"></i></button></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</template>
<style >
	.dp-custom-input {
		box-shadow: 0 0 6px #1976d2;
    color: #5756a2 !important;
    font-size: 20px !important;
    padding-left: 80px !important;
}

.dp-custom-input:hover {
    border-color: #5756a2;
  }
</style>