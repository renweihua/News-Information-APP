<template>
	<view class="uni-tab-bar">
		<scroll-view id="tab-bar" class="uni-swiper-tab" scroll-x :scroll-left="scrollLeft">
			<view v-for="(tab, index) in categorys" :key="tab.ref" :class="['swiper-tab-list',tabIndex==index ? 'active' : '']"
			 :id="tab.ref" :data-current="index" @click="tapTab(index)">{{tab.name}}</view>
		</scroll-view>
		<!-- #ifndef MP-BAIDU -->
		<scroll-view class="list" v-for="(tabItem, idx) in newsList" :key="tabItem.id" v-if="tabIndex === idx" scroll-y
		 @scrolltolower="loadMore(idx)">
			<block v-for="(newsItem, newsIndex) in tabItem.data" :key="newsItem.id">
				<uni-media-list :options="newsItem" @click="goDetail(newsItem.id)"></uni-media-list>
			</block>
			<view class="uni-tab-bar-loading">
				<view class="loading-more">{{loadingText}}</view>
			</view>
		</scroll-view>
		<!-- #endif -->
		<!-- #ifdef MP-BAIDU -->
		<view class="scroll-wrap" v-for="(tabItem, idx) in newsList" :key="idx">
			<scroll-view class="list" v-if="tabIndex === idx" scroll-y @scrolltolower="loadMore(idx)" :style="scrollViewHeight">
				<block v-for="(newsItem, newsIndex) in tabItem.data" :key="newsIndex">
					<uni-media-list :options="newsItem" @click="goDetail(newsItem.id)"></uni-media-list>
				</block>
				<view class="uni-tab-bar-loading">
					<view class="loading-more">{{loadingText}}</view>
				</view>
			</scroll-view>
		</view>
		<!-- #endif -->
	</view>
</template>
<script>
	import uniMediaList from '@/components/uni-media-list/uni-media-list.vue';
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';

	import {
		friendlyDate
	} from '@/common/util.js';

	export default {
		components: {
			uniMediaList,
			uniLoadMore
		},
		data() {
			return {
				loadingText: {
					contentdown: '上拉加载更多',
					contentrefresh: '正在加载...',
					contentnomore: '没有更多数据了'
				},
				scrollLeft: 0,
				refreshing: false,
				refreshText: '下拉可以刷新',
				newsList: [],
				tabIndex: 0,
				categorys : [{}],
			}
		},
		computed: {
			scrollViewHeight() {
				return 'height:' + (uni.getSystemInfoSync().windowHeight) + 'px';
			}
		},
		onLoad: function() {
			// 初始化列表信息
			this.getCategorys(this.onloadFunction);
		},
		methods: {
			onloadFunction(){
				this.categorys.forEach((category, index) => {
					this.newsList.push({
						id: 'category' + index,
						data: [],
						requestParams: {
							category_id: category.category_id,
							page : 1,
							maxpage : 2,
						},
						loadingText: '加载中...'
					});
				});
				this.getList();
			},
			getCategorys(callback) { //分类
				uni.request({
					url: this.$serverUrl + '/api/v1/article-categorys',
					method: 'POST',
					success: (ret) => {
						if (ret.statusCode !== 200) {
							console.log('请求失败', ret)
						} else {
							let data = ret.data.data;
							for(let i = 0; i < data.length; i++)
							{
								data[i]['id'] = data[i]['category_id'];
								data[i]['name'] = data[i]['category_name'];
								data[i]['ref'] = 'category_' + data[i]['category_id'];
							}
							this.categorys = data;
							callback();
						}
					}
				});
			},
			getList(action = 1) {
				let activeTab = this.newsList[this.tabIndex];
				activeTab.requestParams.time = new Date().getTime() + '';
				
				if(activeTab.requestParams.page > activeTab.requestParams.maxpage){
					this.loadingText = '已加载完！';
					return;
				}
				else this.loadingText = '加载中...';
				uni.request({
					url: this.$serverUrl + '/api/v1/articles',
					method: 'POST',
					data: activeTab.requestParams,
					success: (result) => {
						if (result.statusCode == 200) {
							let config = result.data.config;
							const data = result.data.data.data.map((news) => {
								return {
									id: news.article_id,
									datetime: friendlyDate(new Date(news.create_date_his.replace(/\-/g, '/')).getTime()),
									title: news.article_title,
									image_url: news.article_cover,
									source: config.site_web_author,
									read_count: news.read_num
								};
							});
							if (action === 1) {
								activeTab.data = data;
								this.refreshing = false;
							} else {
								data.forEach((news) => {
									activeTab.data.push(news);
								});
							}
							activeTab.requestParams.page = parseInt(result.data.data.cur_page) + parseInt(1);
							activeTab.requestParams.maxpage = parseInt(result.data.data.count_page);
						}
					}
				});
			},
			goDetail(detail) {
				uni.navigateTo({
					url: '/pages/detail/detail?query=' + detail
				});
			},
			loadMore() {
				this.getList(2);
			},
			async changeTab(event) {
				let index = event.detail.current;
				if (this.isClickChange) {
					this.tabIndex = index;
					this.isClickChange = false;
					return;
				}
				let tabBar = await this.getElSize('tab-bar');
				let tabBarScrollLeft = tabBar.scrollLeft;
				let width = 0;

				for (let i = 0; i < index; i++) {
					let result = await this.getElSize(this.categorys[i].ref);
					width += result.width;
				}
				let winWidth = uni.getSystemInfoSync().windowWidth,
					nowElement = await this.getElSize(this.categorys[index].ref),
					nowWidth = nowElement.width;
				if (width + nowWidth - tabBarScrollLeft > winWidth) {
					this.scrollLeft = width + nowWidth - winWidth;
				}
				if (width < tabBarScrollLeft) {
					this.scrollLeft = width;
				}
				this.isClickChange = false;
				this.tabIndex = index;

				// 首次切换后加载数据
				const activeTab = this.newsList[this.tabIndex];
				if (!activeTab || activeTab.data.length === 0) {
					this.getList();
				}
			},
			getNodeSize(node) {
				return new Promise((resolve, reject) => {
					dom.getComponentRect(node, (result) => {
						resolve(result.size);
					});
				});
			},
			onRefresh(event) {
				this.refreshText = '正在刷新...';
				this.refreshing = true;
				this.getList();
			},
			getElSize(id) { //得到元素的size
				return new Promise((res, rej) => {
					uni.createSelectorQuery().select('#' + id).fields({
						size: true,
						scrollOffset: true
					}, (data) => {
						res(data);
					}).exec();
				});
			},
			async tapTab(index) { //点击tab-bar
				if (this.tabIndex === index) {
					return false;
				} else {
					this.tabIndex = index;
					// 首次切换后加载数据
					const activeTab = this.newsList[this.tabIndex];
					if (activeTab.data.length === 0) {
						this.getList();
					}
				}
			}
		}
	}
</script>
<style>
	page {
		background-color: #FFFFFF;
		height: 100%;
		font-size: 11px;
		line-height: 1.8;
	}

	.uni-tab-bar {
		display: flex;
		flex: 1;
		flex-direction: column;
		overflow: hidden;
		height: 100%;
	}

	.uni-tab-bar .list {
		width: 750upx;
		height: calc(100% - 100upx);
		margin-top: 100upx;
	}

	.uni-swiper-tab {
		width: 100%;
		white-space: nowrap;
		line-height: 100upx;
		height: 100upx;
		border-bottom: 1px solid #c8c7cc;
		position: fixed;
		background: #FFFFFF;
		z-index: 999;
		top: var(--window-top);
		left: 0;
	}

	.swiper-tab-list {
		font-size: 30upx;
		width: 150upx;
		display: inline-block;
		text-align: center;
		color: #555;
	}

	.uni-tab-bar .active {
		color: #007AFF;
	}

	.uni-tab-bar .swiper-box {
		flex: 1;
		width: 100%;
		height: calc(100% - 100upx);
		overflow: scroll;
	}

	.uni-tab-bar-loading {
		text-align: center;
		padding: 20upx 0;
		font-size: 14px;
		color: #CCCCCC;
	}
</style>
