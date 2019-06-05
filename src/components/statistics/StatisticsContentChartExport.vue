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
	<div class="asthmadiary_statistics_export">
		<Actions>
			<ActionButton icon="icon-download"
						  @click="createPdf()">
				{{ t('asthmadiary', 'Export PDF') }}
			</ActionButton>
			<ActionButton icon="icon-category-office"
						  @click="createCsv()">
				{{ t('asthmadiary', 'Export CSV') }}
			</ActionButton>
			<ActionButton icon="icon-clippy"
						  @click="createMonthlyReport()">
				{{ t('asthmadiary', 'Export all charts') }}
			</ActionButton>
		</Actions>
	</div>
</template>

<script>
	import jsPDF from 'jspdf';
	import {Parser} from 'json2csv';
	import {saveAs} from 'file-saver';

	import {Actions, ActionButton} from 'nextcloud-vue';

	export default {

		name: "StatisticsContentChartExport",

		components: {
			Actions,
			ActionButton
		},

		models: {
			event: 'create-monthly-report'
		},

		data () {
			return {
				pdf: null,
				csv: null
			}
		},

		props: {
			canvasId: {
				type: String,
				required: true
			},

			chartData: {
				type: Object,
				required: true
			}
		},

		methods: {
			createPdf () {
				this.pdf = new jsPDF('portrait');
				let pic = $(`#${this.canvasId}`);
				let img = pic[0].toDataURL('image/png', 1.0);
				this.pdf.addImage(img, 'PNG', 20, 10, 170, 70);
				this.pdf.save('export.pdf');
			},

			createCsv () {
				let chartDataClone =
					JSON.parse(
						JSON.stringify(this.chartData.datasets[0].data))
						.map((object) => {
							return {date: object.x, value: object.y};
						});

				const fields = ['date', 'value'];
				const parser = new Parser({fields});
				const csv = parser.parse(chartDataClone);
				const blob = new Blob([csv], {type: "text/csv"});
				saveAs(blob, 'export.csv');
			},

			createMonthlyReport () {
				this.$emit('create-monthly-report');
			},
		},
	}
</script>

<style></style>