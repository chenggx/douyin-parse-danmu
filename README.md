# 抖音直播弹幕抓取

## 本项目仅作为个人学习项目。请勿用于非法用途。一切违法行为后果自负！

### 2024-06-28 更新：抓取弹幕已经正常

参考大佬项目改写 <a href="https://github.com/Sjj1024/douyin-live" target="__blank">douyin-live</a>

基于<a href="https://www.workerman.net" target="__blank">workerman</a>开发的超高性能PHP框架

## 使用方法 1

1. 进入 websdk 目录，安装依赖 npm install

```bash
cd websdk && npm install
```

2. 启动node 服务

```bash
cd websdk && node server.js
```

3. 安装 php 依赖

```bash
composer install 
```

4. 启动 php 服务

```bash
php webman test:test start
```

5. 项目启动后会启动 websocket 服务在 4200 端口，通过postman 模拟连接，发送如下消息后就能得到返回的消息

```
{
    "url":"https://live.douyin.com/619592756125"
}
```

## 使用方法-2 容器

1. 构建镜像

```bash
docker build -t dy-danmu .
```

2. 启动容器

直接启动服务
```bash
docker run -d -p 4200:4200 dy-danmu
```

镜像已经上传 dockerHub  可以直接使用如下命令，拉取并运行
```bash
    docker run -d -p 4200:4200  chenggx/dy-danmu
```

使用代理的方式启动服务
```bash
docker run -e HTTP_PROXY=http://用户名:密码@代理地址:端口号 \
           -e HTTPS_PROXY=http://用户名:密码@代理地址:端口号 \
           -e NO_PROXY=localhost,127.0.0.1 \
           -d \
           -p 4200:4200 dy-danmu
```

3. 访问

可以直接在本机访问 ws:127.0.0.1:4200


![联系方式](wechat.jpg)


# 本项目 CDN 加速及安全防护由 Tencent EdgeOne 赞助

<a href="https://edgeone.ai/zh?from=github" target="__blank">官网链接</a>

![Tencent EdgeOne](edgeOne.png)
