# 直接拿来用！10段超有用的Git命令行代码


>    迄今，我已经使用Git很长一段时间了，考虑分享一些不管你是团队开发还是个人项目，都受用的高级git命令。

## 1. 输出最后一次提交的改变
这个命令，我经常使用它 来发送其他没有使用git的人来检查或者集成所修改的。它会输出最近提交的修改内容到一个zip文件中。

```bash
git archive -o ../updated.zip HEAD $(git diff --name-only HEAD^)
```

## 2. 输出两个提交间的改变
类似的，如果你需要输出某两个提交间的改变时，你可以使用这个。

```
git archive -o ../latest.zip NEW_COMMIT_ID_HERE $(git diff --name-only OLD_COMMIT_ID_HERE NEW_COMMIT_ID_HERE)
```

## 3. 克隆 指定的远程分支
如果你渴望只克隆远程仓库的一个指定分支，而不是整个仓库分支，这对你帮助很大。

```bash
git init
git remote add -t BRANCH_NAME_HERE -f origin REMOTE_REPO_URL_PATH_HERE
git checkout BRANCH_NAME_HERE
```

## 4. 应用 从不相关的本地仓库来的补丁
如果你需要其它一些不相关的本地仓库作为你现在仓库的补丁，这里就是通往那里的捷径。

```bash
git --git-dir=PATH_TO_OTHER_REPOSITORY_HERE/.git format-patch -k -1 --stdout COMMIT_HASH_ID_HERE| git am -3 -k
```

## 5. 检测 你的分支的改变是否为其它分支的一部分
cherry命令让我们检测你的分支的改变是否出现在其它一些分支中。它通过+或者-符号来显示从当前分支与所给的分支之间的改变：是否合并了(merged)。.+ 指示没有出现在所给分支中，反之，-就表示出现在了所给的分支中了。这里就是如何去检测：

```bash
git cherry -v OTHER_BRANCH_NAME_HERE
#For example: to check with master branch
git cherry -v master  <br>
```

## 6. 开始一个无历史的新分支
有时，你需要开始一个新分支，但是又不想把很长很长的历史记录带进来，例如，你想在公众区域（开源）放置你的代码，但是又不想别人知道它的历史记录。

```bash
git checkout --orphan NEW_BRANCH_NAME_HERE
```

## 7. 无切换分支的从其它分支Checkout文件
不想切换分支，但是又想从其它分支中获得你需要的文件：

```bash
git checkout BRANCH_NAME_HERE -- PATH_TO_FILE_IN_BRANCH_HERE
```

## 8. 忽略已追踪文件的变动

如果您正在一个团队中工作，而且大家都在同一条branch上面工作，那么您很有可能会经常用到fetch和merge。但是有时候这样会重置您的环境配置文件，如此的话，您就得在每次merge后修改它。使用这一命令，您就能要求git忽视指定文件的变动。这样，下回你再merge的话，这个文件就不会被修改了。

```bash
git update-index --assume-unchanged PATH_TO_FILE_HERE
```

## 9. 检查提交的变动是否是release的一部分

name-rev命令能告诉您一个commit相对于最近一次release的位置。使用这条命令，您就可以检查您所做出的改动是否是release的一部分了。

```bash
git name-rev --name-only COMMIT_HASH_HERE
```

## 10. 使用rebase推送而非merge

如果您正在团队中工作并且整个团队都在同一条branch上面工作，那么您就得经常地进行fetch/merge或者pull。Git中，分支的合并以所提交的merge来记录，以此表明一条feature分支何时与主分支合并。但是在多团队成员共同工作于一条branch的情形中，常规的merge会导致log中出现多条消息，从而产生混淆。因此，您可以在pull的时候使用rebase，以此来减少无用的merge消息，从而保持历史记录的清晰。

```bash
git pull --rebase
```

您也可以将某条branch配置为总是使用rebase推送：

```bash
git config branch.BRANCH_NAME_HERE.rebase true
```

英文出自： [Webdeveloperplus](http://webdeveloperplus.com/general/10-useful-advanced-git-commands/)

中文译文来自：[OSChina](http://www.oschina.net/translate/10-useful-advanced-git-commands)
