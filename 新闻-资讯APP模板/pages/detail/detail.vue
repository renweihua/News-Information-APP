<template>
	<view>
		<view class="banner">
			<image class="banner-img" :src="article.article_cover"></image>
			<view class="banner-title">{{article.article_title}}</view>
		</view>
		<view class="article-meta">
			<text class="article-author">{{config.site_web_author}}</text>
			<text class="article-text">发表于</text>
			<text class="article-time">{{article.create_date_his}}</text>
		</view>
		<view class="article-content">
			<rich-text :nodes="article.article_info ? article.article_info.article_content : ''"></rich-text>
		</view>
		<view class="comment-wrap"></view>
	</view>
</template>

<script>
	const FAIL_CONTENT = '<p>获取信息失败</p>';
	
	import {
		friendlyDate
	} from '@/common/util.js';

	export default {
		data() {
			return {
				article: {},
				config: {},
			}
		},
		onShareAppMessage() {
			return {
				title: this.article.article_title,
				path: '/pages/detail/detail?query=' + JSON.stringify(this.article)
			}
		},
		onLoad(event) {
			// 目前在某些平台参数会被主动 decode，暂时这样处理。
			try {
				this.article_id = JSON.parse(decodeURIComponent(event.query));
			} catch (error) {
				this.article_id = JSON.parse(event.query);
			}

			this.getDetail();
			console.log(this.article);
			uni.setNavigationBarTitle({
				title: this.article.article_title
			});

			
			// 获取分享通道
			uni.getProvider({
				service: "share",
				success: (e) => {
					let data = []
					for (let i = 0; i < e.provider.length; i++) {
						switch (e.provider[i]) {
							case 'weixin':
								data.push({
									name: '分享到微信好友',
									id: 'weixin'
								})
								data.push({
									name: '分享到微信朋友圈',
									id: 'weixin',
									type: 'WXSenceTimeline'
								})
								break;
							case 'qq':
								data.push({
									name: '分享到QQ',
									id: 'qq'
								})
								break;
							default:
								break;
						}
					}
					this.providerList = data;
				},
				fail: (e) => {
					console.log("获取登录通道失败", e);
				}
			});
		},
		methods: {
			getDetail() {
				uni.request({
					url: this.$serverUrl + '/api/v1/articles-detail/' + this.article_id,
					method: 'POST',
					success: (result) => {
						if (result.statusCode == 200) {
							this.article = result.data.data;
							this.article.create_date_his = friendlyDate(new Date(this.article.create_date_his.replace(/\-/g, '/')).getTime());
							this.config = result.data.config;
						} else {
							this.article.article_info.article_content = FAIL_CONTENT;
						}
					}
				});
			}
		}
	}
</script>

<style>
	.banner {
		height: 360upx;
		overflow: hidden;
		position: relative;
		background-color: #ccc;
	}

	.banner-img {
		width: 100%;
	}

	.banner-title {
		max-height: 84upx;
		overflow: hidden;
		position: absolute;
		left: 30upx;
		bottom: 30upx;
		width: 90%;
		font-size: 32upx;
		font-weight: 400;
		line-height: 42upx;
		color: white;
		z-index: 11;
	}

	.article-meta {
		padding: 20upx 40upx;
		display: flex;
		flex-direction: row;
		justify-content: flex-start;
		color: gray;
	}

	.article-text {
		font-size: 26upx;
		line-height: 50upx;
		margin: 0 20upx;
	}

	.article-author,
	.article-time {
		font-size: 30upx;
	}

	.article-content {
		padding: 0 30upx;
		overflow: hidden;
		font-size: 30upx;
		margin-bottom: 30upx;
	}
</style>
