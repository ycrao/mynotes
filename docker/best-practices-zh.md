
# `Dockerfile` 最佳实践指导

> 原始英文位于： https://www.docker.com/blog/intro-guide-to-dockerfile-best-practices/

下文为笔者搭配 AI 翻译，只针对某些段落，并未进行全文一一对照翻译。

### 逐增的构建时间

在构建 `docker` 镜像的开发中，覆盖到缓存是很重要的事情，一旦发生代码变动，需要重新构建镜像。缓存可以帮助我们避免构建中出现不必要的步骤。

#### 技巧 #1 指令顺序决定缓存效果

指令的顺序非常重要。一旦某一步的缓存失效（文件变化或 Dockerfile 该行被修改），它之后的所有步骤缓存都会失效。
 **原则** ：把 **最不常变的内容放前面** ，最常变的内容（如源码）放后面。

![](https://www.docker.com/app/uploads/2019/07/ef41db8f-fe5e-4a78-940a-6a929db7929d-1.jpg)

```diff
FROM debian
-COPY . /app
RUN apt-get update
RUN apt-get -y install openjdk-8-jdk ssh vim
+COPY . /app
CMD ["java", "-jar", "app/target/app.jar"]
```

#### 技巧 #2 拷贝要尽量精准，避免缓存失效

不要随便写 `COPY . /app` ，因为当前目录下任意一个无关文件（如日志、`.git` 、 `IDE` 配置文件）变化都会导致这一层的缓存失效。
推荐只复制真正需要的文件。例如 `Java` 项目只需要最终打好的 `jar` 包：

![](https://www.docker.com/app/uploads/2019/07/0c1d0c4e-406c-468c-b6ba-b71ac68b9c84.jpg)

```diff
FROM debian
RUN apt-get update
RUN apt-get -y install openjdk-8-jdk ssh vim
-COPY . /app
+COPY target/app.jar /app
-CMD ["java", "-jar", "app/target/app.jar"]
+CMD ["java", "-jar", "app/app.jar"]
```

#### 技巧 #3 标记可缓存的单元如 `apt-get update & install`

![](https://www.docker.com/app/uploads/2019/07/2322a39e-bd7e-4a2b-9a8f-548a97dbacb4.jpg)

```diff
FROM debian
-RUN apt-get update
-RUN apt-get -y install openjdk-8-jdk ssh vim
+RUN apt-get update \
+   && apt-get -y install \
+      openjdk-8-jdk ssh vim
COPY target/app.jar /app
CMD ["java", "-jar", "app/app.jar"]
```

`update` 和 `install` 必须放在同一个 `RUN` 指令里，否则一旦 `update` 层缓存失效，你可能会安装到过时的软件包。

### 减少镜像大小

镜像大小是很重要的，更小的镜像意味着更快的部署和更小的攻击面。

#### 技巧 #4 移除不必须的依赖项

![](https://www.docker.com/app/uploads/2019/07/a1b36f64-1a30-45bf-8fcd-4f88437c189e.jpg)

```diff
FROM debian
RUN apt-get update \
-   && apt-get -y install \
+   && apt-get -y install --no-install-recommends \
-      openjdk-8-jdk ssh vim
+      openjdk-8-jdk
COPY target/app.jar /app
CMD ["java", "-jar", "app/app.jar"]
```

使用 `--no-install-recommends` 避免安装“推荐但非必需”的包。调试工具（如 `vim` 、 `strace` ）平时不要装，需要时再临时进入容器装。

#### 技巧 #5 移除包管理器缓存

![](https://www.docker.com/app/uploads/2019/07/363961a4-005e-46fc-963b-f7b690be12ef.jpg)

```diff
FROM debian
RUN apt-get update \
   && apt-get -y install --no-install-recommends \
-      openjdk-8-jdk
+      openjdk-8-jdk \
+  && rm -rf /var/lib/apt/lists/*
COPY target/app.jar /app
CMD ["java", "-jar", "app/app.jar"]
```

如果分两层写，缓存文件仍然会留在镜像里，起不到减小体积的效果。

### 可维护性

#### 技巧 #6 尽可能地使用官方镜像

![](https://www.docker.com/app/uploads/2019/07/f336014d-d2aa-4c1b-a2bd-e1d5d6ed0d93.jpg)

```diff
-FROM debian
-RUN apt-get update \
-   && apt-get -y install --no-install-recommends \
-      openjdk-8-jdk \
-   && rm -rf /var/lib/apt/lists/*
+FROM openjdk
COPY target/app.jar /app
CMD ["java", "-jar", "app/app.jar"]
```

官方镜像已经帮你做好了安全加固和最佳实践，而且多个项目可以共享相同的层，节省存储空间。

#### 技巧 #7 使用特定的标签

![](https://www.docker.com/app/uploads/2019/07/9d991da9-bdb9-4108-8b36-296a5a3772aa.jpg)

```diff
-FROM openjdk:latest
+FROM openjdk:8
COPY target/app.jar /app
CMD ["java", "-jar", "app/app.jar"]
```

`latest` 听起来方便，但它会随着时间变化，导致“今天能构建，三个月后无缓存重建就失败”，请使用具体版本标签。

#### 技巧 #8 选择最小“口味”

![](https://www.docker.com/app/uploads/2019/07/6c486200-5198-4457-86c0-b5275e70e699.jpg)

```bash
REPOSITORY TAG              SIZES
openjdk    8                624MB
openjdk    8-jre            443MB
openjdk    8-jre-slim       204MB
openjdk    8-jre-alpine     83MB
```

* `slim`： 基于精简的 `Debian`
* `alpine`： 基于更小的 `Alpine Linux`（使用 `musl libc` ，体积最小，但偶尔会有兼容性问题）

对于 `Java` ，推荐使用 `eclipse-temurin:17-jre-alpine` 这种只包含 `JRE` 的最小镜像。

### 可复用性

#### 技巧 #9 在容器内从源码构建，保障一致性的环境

![](https://www.docker.com/app/uploads/2019/07/f393ad07-c25d-4241-a40f-c6168e0ba4dd.jpg)

```diff
-FROM openjdk:8-jre-alpine
+FROM maven:3.6-jdk-8-alpine
WORKDIR /app
-COPY app.jar /app
+COPY pom.xml .
+COPY src ./src
+RUN mvn -e -B package
CMD ["java", "-jar", "app/app.jar"]
```

源码才是真理，`Dockerfile` 只是构建蓝图。所有构建依赖都应该在镜像里解决。

#### 技巧 #10 在独立的步骤中拉取依赖

![](https://www.docker.com/app/uploads/2019/07/41ea71ce-11c3-42a3-8d2b-05fe20901745.jpg)

```diff
-FROM maven:3.6-jdk-8-alpine
WORKDIR /app
COPY pom.xml .
+RUN mvn -e -B dependency:resolve
COPY src ./src
RUN mvn -e -B package
CMD ["java", "-jar", "app/app.jar"]
```

把“拉取依赖”和“编译代码”分成两步，提升缓存命中率。只有 `pom.xml` 修改了才会重新拉依赖，大大加快日常开发构建速度。

#### 技巧 #11 使用多阶段构建以移除构建时的依赖（`Docker`官方推荐）

![](https://www.docker.com/app/uploads/2019/07/97ec1992-f0df-4c8f-82a0-e177c230e5c5.jpg)

```diff
-FROM maven:3.6-jdk-8-alpine
+FROM maven:3.6-jdk-8-alpine AS builder
WORKDIR /app
COPY pom.xml .
RUN mvn -e -B dependency:resolve
COPY src ./src
RUN mvn -e -B package
-CMD ["java", "-jar", "app/app.jar"]

+FROM openjdk:8-jre-alpine
+COPY --from=builder /app/target/app.jar /
+CMD ["java", "-jar", "/app.jar"]
```

这是目前最推荐的写法，既保证构建环境一致，又让最终镜像极小，还能充分利用缓存。

![](https://www.docker.com/app/uploads/2019/07/80c3c350-5f7e-4cf1-ab3e-89df755b3c33.jpg)

```dockerfile
FROM maven:3.6-jdk-8-alpine AS builder
WORKDIR /app
COPY pom.xml .
RUN mvn -e -B dependency:resolve
COPY src ./src
RUN mvn -e -B package

FROM openjdk:8-jre-alpine
COPY --from=builder /app/target/app.jar /
CMD ["java", "-jar", "/app.jar"]
```

这样最终镜像里只有 `JRE + jar` 包，不包含 `Maven`、源码、依赖缓存等构建工具，体积最小，同时构建过程完全可重复、缓存友好。