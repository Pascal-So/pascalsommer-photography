# Requirements

A rough requirements specification for the new version of [pascalsommer.ch](http://pascalsommer.ch/).


## Roles of users

Site needs an admin account. Everyone else doesn't log in, posting comments doesn't require an account.


## Entities

The focus is on photos.
A photo can have a description and multiple comments.
Multiple photos are grouped to a post.
The photos don't have a title associated with them, only the post does.
Photos can have tags associated with them. (many to many)

## Functionalities

These are sort of like use cases.


### Accessible to Admin only:

* Upload a photo
  
  Preferably make it possible to upload multiple photos at once.

* Publish a post
  
  Should be possible to set a future publishing date and time. Unpublished posts should be visible to admin anyway, but visually distinct from published posts

* Delete comments

* Receive a notification when a new comment is posted

* Edit post

  This is mostly about title and photos contained (also order of photos in post), but might also include timestamp if possible

* Edit photo description

  Even on live photos

* Add/remove tags to a photo

* Edit tag list

* Change name of a tag, keeping the existing relations


### Accessible to everyone

* Overview of all posts

* View an individual photo

  The browser zoom should work when the user is trying to see it at any desired scale.

* Post a comment on a photo

* View comments and tags of a photo

* Filter photos by tag

  If possible allow user to filter for the intersection of multiple tags as well.


## Non-functional requirements

* Shareable URLs

  A non-logged in user should be able to just copy the url, send that to a friend, who can then paste it and see the exact same view, at any time (save for text or other input the user is currently entering, and for formating due to differing screen size or browser settings)

* Orientation - the user should be able to keep track of where they are right now

  This might rule out an individual page per post, and just leave the overall view (with or without filters applied) and the single photo view.

* It should be always visible to the admin that they are logged in, the logout button should be quickly reachable

* Unicode should be supported in comments


## Sitemap

* Main overview

  Lists all published photos or results of filter if applied, grouped by posts. Contains link to [codelis.ch](http://codelis.ch/) and Twitter, Youtube, GitHub, Medium. Filter should be adjustable from this page. All tags as well as an archive overview should be listed somewhere on here.

  * About me

  * Single photo view

    Comments if possible not in a subpage, but below the photo

* Admin area

  Have a nice overview, so the admin doesn't have to rely on editing the url to get where they want to go

  * Overview of all photos

    This would default to unpublished photos, but can show a list of all photos as well, with some filters

    * Photo upload page

    * Photo edit page

      This will probably be the same as the single photo view for visitors, but without the comments, and with some visual indication that this isn't a live photo.

  * Tags create/edit/link page

  * Post creation page

  * Overview of all posts

    Maybe add a datum range filter here

    * Post editing page

      Should look quite similar to the creation page. Could reuse the form.


## Usage

This is getting more in to the details of how the user or admin interacts with the page. The current version of the site serves as a baseline, this is only to specify what differs from the current version.


### Admin logged in

On the main overview, the admin should see an edit button on every post.

On the photo view, the admin should see an edit page, that lets them edit the description.

The admin should see a delete button on the comments (will still be asked for confirmation)


### Visitor viewing page

The tag and date filter should be easily visible, but not obstruct the photos on the main page


## Tables

### Posts

id
title
date, which can be in the future, to indicate, that the post will be published in the future automatically

### Photos

id
post_id, this can be null to indicate that the photo is still in the staging area
description
path
weight, to indicate the order in the post or staging area

### Tags

id
parent_tag_id, can be null
name

### PhotoTag

photo_id
tag_id

### Comments

id
name
content
photo_id