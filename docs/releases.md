---
layout: default
title: Release Notes
redirect_from:
    - /changelog/
---

# Release Notes

{% assign releases = site.github.releases %}
{% for release in releases %}

## {{ release.name }} - {{ release.published_at | date: "%Y-%m-%d" }}

{{ release.body | replace:'```', '~~~' | markdownify }}
{% endfor %}
