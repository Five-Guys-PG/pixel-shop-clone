#!/bin/sh

CURRENT_BRANCH=${GITHUB_HEAD_REF}
echo "Checking commit messages for ${CURRENT_BRANCH} against dev"

git fetch origin dev:dev
BASE_BRANCH=$(git merge-base dev ${CURRENT_BRANCH})

if git log --format="%s" $BASE_BRANCH..${CURRENT_BRANCH} | grep -vE '^((init|feat|fix|docs|chore|refactor)( #[0-9]+)?: .{1,100}|([Mm]erge|[Rr]evert).*)'
then
    echo "Invalid commit message found."
    exit 1
else
    echo "All commit messages are valid."
fi