<!--
  - /**
  -  *
  -  * @copyright Copyright (c) 2019, Nils Griebner (nils@nils-griebner.de)
  -  *
  -  * @license GNU AGPL version 3 or any later version
  -  *
  -  * This program is free software: you can redistribute it and/or modify
  -  * it under the terms of the GNU Affero General Public License as
  -  * published by the Free Software Foundation, either version 3 of the
  -  * License, or (at your option) any later version.
  -  *
  -  * This program is distributed in the hope that it will be useful,
  -  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  -  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  -  * GNU Affero General Public License for more details.
  -  *
  -  * You should have received a copy of the GNU Affero General Public License
  -  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -  *
  -  */
  -->

<template>
	<div class="app-content-details">
		<statistics-content-header/>
		<div id="asthmadiary_statistics_wrapper">
			<statistics-content-chart-export
					v-bind:canvas-id="valueChartCanvasId"
					v-bind:chart-data="valueChartData"
					@create-monthly-report="createMonthlyReport()"
			/>
			<div class="asthmadiary_statistics_chart_wrapper">
				<statistics-line-chart v-bind:chart-data="valueChartData"
									   v-bind:options="chartOptions"
									   v-bind:chart-id="valueChartCanvasId"
									   v-if="!loading"
				/>
			</div>
			<statistics-content-chart-export
					v-bind:canvas-id="breathlessnessChartCanvasId"
					v-bind:chart-data="breathlessnessChartData"
					@create-monthly-report="createMonthlyReport()"
			/>
			<div class="asthmadiary_statistics_chart_wrapper">
				<statistics-line-chart
						v-bind:chart-data="breathlessnessChartData"
						v-bind:options="chartOptions"
						v-bind:chart-id="breathlessnessChartCanvasId"
						v-if="!loading"
				/>
			</div>
			<statistics-content-chart-export
					v-bind:canvas-id="coughChartCanvasId"
					v-bind:chart-data="coughChartData"
					@create-monthly-report="createMonthlyReport()"
			/>
			<div class="asthmadiary_statistics_chart_wrapper">
				<statistics-line-chart v-bind:chart-data="coughChartData"
									   v-bind:options="chartOptions"
									   v-bind:chart-id="coughChartCanvasId"
									   v-if="!loading"
				/>
			</div>
			<statistics-content-chart-export
					v-bind:canvas-id="phlegmChartCanvasId"
					v-bind:chart-data="phlegmChartData"
					@create-monthly-report="createMonthlyReport()"
			/>
			<div class="asthmadiary_statistics_chart_wrapper">
				<statistics-line-chart v-bind:chart-data="phlegmChartData"
									   v-bind:options="chartOptions"
									   v-bind:chart-id="phlegmChartCanvasId"
									   v-if="!loading"
				/>
			</div>
		</div>
	</div>
</template>

<script>
	import isMobile from 'nextcloud-vue/dist/Mixins/isMobile';
	import StatisticsLineChart from './StatisticsLineChart';
	import StatisticsContentChartExport from './StatisticsContentChartExport';
	import StatisticsContentHeader from './StatisticsContentHeader';

	import {Actions, ActionButton} from 'nextcloud-vue';

	import {getDaysOfMonth} from '../../services/utils';
	import {API_DATE_FORMAT} from '../../services/utils';
	import {MEASUREMENT_LIST_NA_VALUE} from '../../services/utils';

	import {mapState, mapGetters} from 'vuex';
	import jsPDF from 'jspdf';

	export default {

		name: "StatisticsContent",

		components: {
			StatisticsLineChart,
			StatisticsContentChartExport,
			StatisticsContentHeader,
			Actions,
			ActionButton
		},

		mixins: [isMobile],

		data () {
			return {
				loading: true,
				valueChartData: {},
				breathlessnessChartData: {},
				phlegmChartData: {},
				coughChartData: {},
				chartOptions: null,
				chartBorderColor: '#0082c9',
				valueChartCanvasId: "asthmadiary_value_chart_canvas",
				breathlessnessChartCanvasId: "asthmadiary_breathlessness_chart_canvas",
				phlegmChartCanvasId: "asthmadiary_phlegm_chart_canvas",
				coughChartCanvasId: "asthmadiary_cough_chart_canvas",
			}
		},

		watch: {
			getValues: {
				handler: function () {
					if (!this.checkValuesEmpty()) {
						this.calculateChartData();
					} else {
						this.calculateEmptyData();
					}
				},
				deep: true
			}
		},

		computed: {
			...mapState({
				values: state => state.measurements.values,
				measurements: state => state.measurements.measurements,
				date: state => state.measurements.date
			}),

			...mapGetters([
				'getValues',
				'getBestValueOfMeasurement',
				'getBreathlessnessValues',
				'getCoughValues',
				'getPhlegmValues'
			]),
		},

		methods: {

			checkValuesEmpty () {
				return this.values.length === 0;
			},

			calculateLabels () {
				return getDaysOfMonth(this.date).map((date) => {
					return date.format(API_DATE_FORMAT);
				});
			},

			calculateValueData () {
				let data = getDaysOfMonth(this.date).map((date) => {
					let value = this.getBestValueOfMeasurement(
						date.format(API_DATE_FORMAT));

					return {
						x: date.format(API_DATE_FORMAT),
						y: value === MEASUREMENT_LIST_NA_VALUE ? 0 : parseInt(value)
					}
				});

				this.valueChartData = {
					labels: this.calculateLabels(),
					datasets: [{
						label: t('asthmadiary', 'Peak-Flow Values'),
						fill: false,
						borderColor: this.chartBorderColor,
						backgroundColor: this.chartBorderColor,
						data: data
					}]
				};
			},

			calculateBreathlessnessData () {
				let breathlessnessValues = this.getBreathlessnessValues;
				let data = getDaysOfMonth(this.date).map((date, index) => {
					return {
						x: date.format(API_DATE_FORMAT),
						y: breathlessnessValues[index]
					}
				});

				this.breathlessnessChartData = {
					labels: this.calculateLabels(),
					datasets: [{
						label: t('asthmadiary', 'Breathlessness'),
						fill: false,
						borderColor: this.chartBorderColor,
						backgroundColor: this.chartBorderColor,
						data: data
					}]
				};
			},

			calculateCoughData () {
				let coughValues = this.getCoughValues;
				let data = getDaysOfMonth(this.date).map((date, index) => {
					return {
						x: date.format(API_DATE_FORMAT),
						y: coughValues[index]
					}
				});

				this.coughChartData = {
					labels: this.calculateLabels(),
					datasets: [{
						label: t('asthmadiary', 'Cough'),
						fill: false,
						borderColor: this.chartBorderColor,
						backgroundColor: this.chartBorderColor,
						data: data
					}]
				};
			},

			calculatePhlegmData () {

				let phlegmValues = this.getPhlegmValues;
				let data = getDaysOfMonth(this.date).map((date, index) => {
					return {
						x: date.format(API_DATE_FORMAT),
						y: phlegmValues[index]
					}
				});

				this.phlegmChartData = {
					labels: this.calculateLabels(),
					datasets: [{
						label: t('asthmadiary', 'Phlegm'),
						fill: false,
						borderColor: this.chartBorderColor,
						backgroundColor: this.chartBorderColor,
						data: data
					}]
				};
			},

			calculateEmptyData () {
				let emptyData = {
					labels: this.calculateLabels(),
					datasets: []
				};

				this.valueChartData = emptyData;
				this.breathlessnessChartData = emptyData;
				this.coughChartData = emptyData;
				this.phlegmChartData = emptyData;
			},

			calculateChartData () {
				if (this.checkValuesEmpty()) {
					this.calculateEmptyData();
					this.getChartOptions();
				} else {
					this.calculateValueData();
					this.calculateBreathlessnessData();
					this.calculatePhlegmData();
					this.calculateCoughData();
					this.getChartOptions();
				}
			},

			getChartOptions () {
				this.chartOptions = {
					maintainAspectRatio: false,
					legend: {
						position: 'bottom'
					},
					scales: {
						xAxes: [{
							type: 'time',
							time: {
								parser: 'YYY-MM-DD',
								unit: 'day',
								stepSize: this.isMobile ? 14 : 1
							},
						}],
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
			},

			createMonthlyReport () {
				let pdf = new jsPDF('portrait');

				let valuePic = $(`#${this.valueChartCanvasId}`);
				let breathlessnessPic = $(`#${this.breathlessnessChartCanvasId}`);
				let coughPic = $(`#${this.coughChartCanvasId}`);
				let phlegmPic = $(`#${this.phlegmChartCanvasId}`);

				let valueImg = valuePic[0].toDataURL('image/png', 1.0);
				let breathlessnessImg = breathlessnessPic[0].toDataURL('image/png', 1.0);
				let coughImg = coughPic[0].toDataURL('image/png', 1.0);
				let phlegmImg = phlegmPic[0].toDataURL('image/png', 1.0);

				pdf.addImage(valueImg, 'PNG', 20, 20, 170, 50);
				pdf.addImage(breathlessnessImg, 'PNG', 20, 80, 170, 50);
				pdf.addImage(coughImg, 'PNG', 20, 140, 170, 50);
				pdf.addImage(phlegmImg, 'PNG', 20, 200, 170, 50);

				pdf.save('export.pdf');
			},
		},

		mounted () {
			this.calculateChartData();
			this.loading = false;
		}
	}
</script>

<style></style>