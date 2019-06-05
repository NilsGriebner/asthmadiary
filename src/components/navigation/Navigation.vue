<!--
  /**
   *
   * @copyright Copyright (c) 2019, Nils Griebner (nils@nils-griebner.de)
   *
   * @license GNU AGPL version 3 or any later version
   *
   * This program is free software: you can redistribute it and/or modify
   * it under the terms of the GNU Affero General Public License as
   * published by the Free Software Foundation, either version 3 of the
   * License, or (at your option) any later version.
   *
   * This program is distributed in the hope that it will be useful,
   * but WITHOUT ANY WARRANTY; without even the implied warranty of
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   * GNU Affero General Public License for more details.
   *
   * You should have received a copy of the GNU Affero General Public License
   * along with this program.  If not, see <http://www.gnu.org/licenses/>.
   *
   */
  -->

<script>
	import {mapState} from 'vuex'
	import {
		AppNavigation,
		AppNavigationSettings,
		AppNavigationItem
	} from 'nextcloud-vue';

	import {NAVIGATION_DATE_FORMAT} from '../../services/utils';

	import SettingsSection from './SettingsSection';

	export default {

		name: 'Navigation',

		components: {
			AppNavigation,
			AppNavigationSettings,
			AppNavigationItem,
			SettingsSection
		},

		computed: {
			...mapState({
				date: state => state.measurements.date,
				currentLocale: state => state.measurements.currentLocale,
			}),

			navigationDateFormat () {
				return NAVIGATION_DATE_FORMAT;
			},

			/**
			 * Build menu entries
			 */
			menu () {
				return [
					{
						text: t('asthmadiary', 'Symptoms and Measurements'),
						icon: "icon-category-monitoring",
						action: () => this.$router.push('measurements')
					},
					{
						text: t('asthmadiary', 'Statistics'),
						icon: 'icon-picture',
						action: () => this.$router.push('statistics')
					}
				]
			}
		},

		methods: {
			addMonth () {
				this.$store.dispatch('addMonth').then(() => {
					this.$forceUpdate();
				});
			},

			subtractMonth () {
				this.$store.dispatch('subtractMonth').then(() => {
					this.$forceUpdate();
				});
			},

			getDateText () {
				let date = this.date;
				date.locale(this.currentLocale);
				return date.format(this.navigationDateFormat);
			},
		}
	}
</script>

<template>
	<AppNavigation>
		<!-- App Navigation new button -->
		<div class="app-navigation-new">
			<div id="nav_datepicker_wrapper">
				<button v-on:click='subtractMonth()'
						type="button"
						class="button icon-view-previous">
					&nbsp;
				</button>
				<div id="nav_date_div">
					<span id="nav_date_text">{{ getDateText() }}</span>
				</div>
				<button v-on:click='addMonth()'
						type="button"
						class="button icon-view-next">
					&nbsp;
				</button>
			</div>
		</div>
		<ul>
			<app-navigation-item v-for="item in menu" :key="item.text"
								 :item="item"/>
		</ul>
		<AppNavigationSettings>
			<SettingsSection/>
		</AppNavigationSettings>
	</AppNavigation>
</template>

<style></style>
