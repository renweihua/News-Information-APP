<template>
	<view class="about">
		<view class="content">
			<view class="qrcode">
				<!-- #ifdef APP-PLUS -->
				<image :src="config.site_web_logo" @longtap="save"></image>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<image :src="config.site_web_logo"></image>
				<!-- #endif -->
				<text class="tip"> {{config.site_web_author}} </text>
			</view>
			<view class="desc">
				前端：基于uni-app开发。
				<br>
				后端：基于cnpscy-basic后台搭建开发【{{config.site_web_author}}】。
			</view>
			<view class="source">
				<view class="title">开发者：</view>
				<view class="source-list">
					<view class="source-cell">
						<text space="nbsp">1. </text>
						<text>名称：{{config.site_web_author}}</text>
					</view>
					<view class="source-cell">
						<text space="nbsp">2. </text>
						<text>QQ：{{config.site_web_qq}}</text>
					</view>
					<view class="source-cell">
						<text space="nbsp">3. </text>
						<text>微信：{{config.site_web_wx}}</text>
					</view>
					<view class="source-cell">
						<text space="nbsp">4. </text>
						<text>邮箱：{{config.site_web_email}}</text>
					</view>
					<view class="source-cell">
						<text space="nbsp">5. </text>
						<text @click="openLink" class="link">项目Github：{{sourceLink}}</text>
					</view>
				</view>
			</view>
		</view>
		<!-- #ifdef APP-PLUS -->
		<view class="version">
			当前版本：{{version}}
		</view>
		<!-- #endif -->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				providerList: [],
				config : [],
				version: '',
				sourceLink: 'https://github.com/renweihua/News-Information-APP'
			}
		},
		onLoad() {
			this.getConfig();

			// #ifdef APP-PLUS
			this.version = plus.runtime.version;
			// #endif
		},
		methods: {
			getConfig() {
				uni.request({
					url: this.$serverUrl + '/api/v1/configs',
					method: 'POST',
					success: (result) => {
						this.config = result.data.data;
						this.getConfig = this.config.site_web_logo;
					}
				});
			},
			// #ifdef APP-PLUS
			save() {
				uni.showActionSheet({
					itemList: ['保存图片到相册'],
					success: () => {
						plus.gallery.save(this.config.site_web_logo, function() {
							uni.showToast({
								title: '保存成功',
								icon: 'none'
							})
						}, function() {
							uni.showToast({
								title: '保存失败，请重试！',
								icon: 'none'
							})
						});
					}
				})
			},
			// #endif
			openLink() {
				if (plus) {
					plus.runtime.openURL(this.sourceLink);
				} else {
					window.open(this.sourceLink);
				}
			}
		}
	}
</script>

<style>
	page,
	view {
		display: flex;
	}

	page {
		min-height: 100%;
		background-color: #FFFFFF;
	}

	image {
		width: 360upx;
		height: 360upx;
	}

	.about {
		flex-direction: column;
		flex: 1;
	}

	.content {
		flex: 1;
		padding: 30upx;
		flex-direction: column;
		justify-content: center;
	}

	.qrcode {
		display: flex;
		align-items: center;
		flex-direction: column;
	}

	.qrcode .tip {
		margin-top: 20upx;
	}
	.qrcode img {
		border-radius: 50%;
	}

	.desc {
		margin-top: 30upx;
		display: block;
	}

	.code {
		color: #e96900;
		background-color: #f8f8f8;
	}

	button {
		width: 100%;
		margin-top: 40upx;
	}

	.version {
		height: 80upx;
		line-height: 80upx;
		justify-content: center;
		color: #ccc;
	}

	.source {
		margin-top: 30upx;
		flex-direction: column;
	}

	.source-list {
		flex-direction: column;
	}

	.link {
		color: #007AFF;
	}
</style>
