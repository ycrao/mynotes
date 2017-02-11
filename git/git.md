# Git设置与使用帮助


>    参考网站：  
http://rogerdudler.github.io/git-guide/index.zh.html  
http://git.oschina.net/progit/  

### 1. 检查是否已经有SSH Key

```bash
cd ~/.ssh
```

### 2. 生成一个新的SSH Key

```bash
ssh-keygen -t rsa -C "admin@example.com"
```
请自行替换 `admin@example.com` 为自己的电邮地址。之后直接回车，不用填写东西。之后会让你输入密码短语（`passphrase`）。然后就生成一个目录 `.ssh` ，里面有两个文件： `id_rsa` 和  `id_rsa.pub` 。

### 3. Git配置

**1. 配置用户信息**

```bash
git config --global user.name "John Doe"
git config --global user.email "johndoe@example.com"
```

请自行替换 `John Doe` 为自己常用的英文网络`ID` ， `johndeo@exaple.com` 为自己常用的 `Email` 。

**2. 获取帮助**

想了解 `Git` 的各式工具该怎么用，可以阅读它们的使用帮助，方法有三：

```bash
git help <verb>
git <verb> --help
man git-<verb>
```

比如，要学习 `config` 命令可以怎么用，运行：

```bash
git help config
```

### 4. Git相关操作命令


**1. 创建新仓库**

创建新文件夹，打开，然后执行：

```bash
git init
```

以创建新的 git 仓库。

**2. 检出仓库**

执行如下命令以创建一个本地仓库的克隆版本：

```bash
git clone /path/to/repository 
```

如果是远端服务器上的仓库，你的命令会是这个样子：

```bash
git clone username@host:/path/to/repository
```

**3. 工作流**

你的本地仓库由 `git` 维护的三棵“树”组成。第一个是你的 工作目录，它持有实际文件；第二个是 缓存区（`Index`），它像个缓存区域，临时保存你的改动；最后是 `HEAD`，指向你最近一次提交后的结果。

**4. 添加与提交**

你可以计划改动（把它们添加到缓存区），使用如下命令：

```bash
git add <filename>
git add *
```

这是 git 基本工作流程的第一步；使用如下命令以实际提交改动：

```bash
git commit -m "代码提交信息"
```

现在，你的改动已经提交到了 `HEAD`，但是还没到你的远端仓库。

**5. 推送改动**

你的改动现在已经在本地仓库的 HEAD 中了。执行如下命令以将这些改动提交到远端仓库：

```bash
git push origin master
```

可以把 `master` 换成你想要推送的任何分支。 

如果你还没有克隆现有仓库，并欲将你的仓库连接到某个远程服务器，你可以使用如下命令添加：

```bash
git remote add origin <server>
```

如此你就能够将你的改动推送到所添加的服务器上去了。

**6. 分支**

分支是用来将特性开发绝缘开来的。在你创建仓库的时候，`master` 是“默认的”。在其他分支上进行开发，完成后再将它们合并到主分支上。

创建一个叫做 `feature_x` 的分支，并切换过去：

```bash
git checkout -b feature_x
```

切换回主分支：

```bash
git checkout master
```

再把新建的分支删掉：

```bash
git branch -d feature_x
```

除非你将分支推送到远端仓库，不然该分支就是 不为他人所见的：

```bash
git push origin <branch>
```

**7. 更新与合并**

要更新你的本地仓库至最新改动，执行：

```bash
git pull
```

以在你的工作目录中 获取（`fetch`） 并 合并（`merge`） 远端的改动。
要合并其他分支到你的当前分支（例如 `master`），执行：

```bash
git merge <branch>
```

两种情况下，`git` 都会尝试去自动合并改动。不幸的是，自动合并并非次次都能成功，并可能导致 冲突（`conflicts`）。 这时候就需要你修改这些文件来人肉合并这些 冲突（`conflicts`） 了。改完之后，你需要执行如下命令以将它们标记为合并成功：

```bash
git add <filename>
```
在合并改动之前，也可以使用如下命令查看：

```bash
git diff <source_branch> <target_branch>
```

**8. 标签**

在软件发布时创建标签，是被推荐的。这是个旧有概念，在 `SVN` 中也有。可以执行如下命令以创建一个叫做 `1.0.0` 的标签：

```bash
git tag 1.0.0 1b2e1d63ff
```

`1b2e1d63ff` 是你想要标记的提交 `ID` 的前 10 位字符。使用如下命令获取提交 `ID`：

```bash
git log
```
你也可以用该提交 `ID` 的少一些的前几位，只要它是唯一的。

**9. 替换本地改动**

假如你做错事（自然，这是不可能的），你可以使用如下命令替换掉本地改动：

```bash
git checkout -- <filename>
```

此命令会使用 `HEAD` 中的最新内容替换掉你的工作目录中的文件。已添加到缓存区的改动，以及新文件，都不受影响。

假如你想要丢弃你所有的本地改动与提交，可以到服务器上获取最新的版本并将你本地主分支指向到它：

```bash
git fetch origin
git reset --hard origin/master
```

**10.在命令行中创建并提交Git仓库**

```bash
mkdir example
cd example
git init
echo "# example" >> README.md
git add README.md
git commit -m "first commit"
git remote add origin git@github.com:ycrao/example.git
git push -u origin master
```

**11. 在命令行提交已有项目**

```bash
cd existing_git_repo
git remote add origin git@github.com:ycrao/existing_git_repo.git
git push -u origin master
```

注意：第10、11条示例中 `git@github.com:ycrao/example.git` 或 `git@github.com:ycrao/existing_git_repo.git` 是仓库 `SSH` 方式地址，一般源码托管服务商（如 `GitHub` 和 `Coding` ）会在仓库页面中告知你，请根据实际情况与操作自行替换。