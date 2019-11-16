---
name: New release
about: Checklist for new releases.
title: 'New patch/minor/major release'
labels: ''

---

**Milestone:** (add url)

<!-- See https://semver.org if you're not sure what type of release this is -->
**Release type:** patch/minor/major

### Code checklist

- [ ] Start release branch with Git Flow: `git flow release start <version>`
- [ ] Update changelog:
    - [ ] In the `CHANGELOG.md` file
    - [ ] In the `readme.txt` file
- [ ] Update version strings and commit the changes. Depending on release type, run `gulp release:type`. E.g. for a patch release, run `gulp release:patch`
- [ ] Run `gulp i18n` and commit the changes
- [ ] Add new translation files (`*.po` or `*.mo` files if applicable)
- [ ] Check tests are passing (Travis) 
- [ ] Finish release branch with Git Flow `git flow release finish <version>`
- [ ] Sync changes with GitHub (these assume your `origin` remote is pointing to this repo)
    - [ ] `git checkout master && git push origin master`
    - [ ] `git push origin --tags`
    - [ ] `git checkout develop && git push origin develop`
    
### WordPress.org plugin repo checklist
- [ ]

### Non-code checklist:
- [ ] Update documentation
- [ ] Publish 

