Hype MailchimpBundle for API V2.0
========================

Symfony2.x bundle for 
[MailChimp](http://apidocs.mailchimp.com/api/2.0/) API V2 and [Export API](http://apidocs.mailchimp.com/export/1.0/) API V1
Wrapper bundle that makes accessing Mailchimp functions easily in object oriented using method chaining 


**License**

HypeMailChimp bundle released under MIT LICENSE 

#Supported API Methods

**Campaigns related**

1. `campaigns/create`
2. `campaigns/content`
2. `campaigns/list`
2. `campaigns/delete`
2. `campaigns/pause`
2. `campaigns/ready`
2. `campaigns/replicate`
2. `campaigns/ready`
2. `campaigns/resume`
2. `campaigns/send`
2. `campaigns/send-test`
2. `campaigns/segment-test`
2. `campaigns/schedule`
2. `campaigns/schedule-batch`
2. `campaigns/unschedule`
2. `campaigns/update`

**Lists related**

1. `lists/abuse-reports`
1. `lists/activity`
1. `lists/subscribe`
1. `lists/unsubscribe`
1. `lists/member-info`
1. `lists/interest-groupings`
1. `lists/interest-grouping-add`
1. `lists/interest-grouping-del`
1. `lists/interest-grouping-update`
1. `lists/interest-group-add`
1. `lists/interest-group-update`
1. `lists/interest-group-del`

**Templates related**

1. `templates/add`
1. `templates/list`
1. `templates/del`
1. `templates/info`
1. `templates/undel`



**Export API**

1. `list`
2. `campaignSubscriberActivity`

Need support for a method not on the list submit an [issue](HypeMailchimpBundle/issues/new)

## Setup

### Step 1: Download HypeMailchimp using composer

Add HypeMailchimp in your composer.json:

```js
{
    "require": {
        "ahmedsamy/hype-mailchimp-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update "ahmedsamy/hype-mailchimp-bundle"
```

Composer will install the bundle to your project's `vendor/ahmedsamy/hype` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Hype\MailchimpBundle\HypeMailchimpBundle(),
    );
}
```

### Step 3: Add configuration

``` yml
# app/config/config.yml
hype_mailchimp:
    api_key: xxxxxxx-us5
    default_list: xxxxxxxx
    ssl: true #optional configuring curl connection
```

## Usage

**Using service**

``` php
<?php
        $mailchimp = $this->get('hype_mailchimp');
?>
```

##Examples

###Create new campaign
``` php
<?php 
    $mc = $this->get('hype_mailchimp');
        $data = $mc->getCampaign()->create('regular', array(
            'list_id' => '93419bbdc0',
            'subject' => 'test created subject',
            'from_email' => 'ahmed.samy.cs@gmail.com',
            'from_name' => 'Ahmed Samy',
            'to_name' => 'fans'
                ), array(
            'html' => '<h5>Html content</h5>',
            'sections' => array(),
            'text' => 'test',
            'url' => 'http://www.example.com',
            'archive' => 'test'
        ));
        var_dump($data);
?>
```
###Delete existing campaign
``` php
<?php 
     $mc = $this->get('hype_mailchimp');
     $data = $mc->getCampaign()
                ->setCi('1088b4ed65')
                ->del();

        var_dump($data);
?>
```

###Send campaign
``` php
<?php 
     $mc = $this->get('hype_mailchimp');
     $data = $mc->getCampaign()
                ->setCi('1088b4ed65')
                ->send();

        var_dump($data);
?>
```

###Subscribe new user to list
``` php
<?php 
     $mc = $this->get('hype_mailchimp');
     $data = $mc->getList()
                ->addMerge_vars(
                        array(
                            'mc_notes' => 'test notes'
                ))
                ->subscribe('moneky@suitMonkry.com');
        var_dump($data);
?>
```
**Note** that the user will be subscriber to the default list set in `config.yml` 
if you want to change the list for this time only, you can use 
``` php
<?php 
     $mc = $this->get('hype_mailchimp');
     $data = $mc->getList()
                ->setListId('xxxxxxx')
                ->addMerge_vars(
                        array(
                            'mc_notes' => 'test notes'
                ))
                ->subscribe('moneky@suitMonkry.com');
?>
```
