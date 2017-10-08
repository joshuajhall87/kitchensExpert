<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Youtube;

use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteAccount;
use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteService;
use WPRemoteMediaExt\RemoteMediaExt\Library\MediaTemplate;
use WPRemoteMediaExt\WPCore\View;
use WPRemoteMediaExt\WPForms\FieldSet;

class Service extends AbstractRemoteService
{
    protected $key = "AIzaSyB9Dk0uisM1dAnvAT0AKgVBwAF3TlIC-aI";

    public function __construct()
    {
        parent::__construct(__('Youtube', 'remote-medias-lite'), 'youtube');

        $client = Client::factory();
        $this->setClient($client);
    }

    public function init()
    {
        if (is_admin()) {

            $this->mediaSettings = array('uploadTemplate' => 'media-upload-youtube-upgrade');
            $this->hook(new MediaTemplate(new View($this->getViewsPath().'admin/media-upload-youtube.php')));

            //FieldSets need to be initialized early because they hook needed JS and CSS for fields added
            $this->initFieldSet();
        }
    }

    public function initFieldSet()
    {
        $this->fieldSet = new FieldSet();
        $field = array(
            'label' => __("YouTube User ID", 'remote-medias-lite'),
            'type' => 'Text',
            'class' => $this->getSlug(),
            'id' => 'remote_user_id',
            'name' => 'account_meta['.$this->getSlug().'][youtube_remote_user_id]',
            'desc' => __("Insert the Youtube User ID for this library", 'remote-medias-lite'),
        );
        $this->fieldSet->addField($field);
        // $field = array(
        //     'label' => __("Feed Type", 'remote-medias-lite'),
        //     'type' => 'select',
        //     'class' => $this->getSlug(),
        //     'id' => 'youtubeFeedType',
        //     'name' => 'account_meta['.$this->getSlug().'][youtubeFeedType]',
        //     'options' => array(
        //         'uploaded' => __("Uploaded by this user", 'remote-medias-lite'),
        //         'favorites' => __("Favorites of this user", 'remote-medias-lite'),
        //     ),
        //     'desc' => __("Insert the Youtube User ID for this library", 'remote-medias-lite'),
        // );
        // $this->fieldSet->addField($field);
    }

    public function setAccount(AbstractRemoteAccount $account)
    {
        $this->account = $account;
    }

    public function validate()
    {
        $channels = array(
            'items' => array()
        );
        //Using getUserChannelsId has a quota cost of 0
        try {
            $channels = $this->getUserChannelsId(1);
        } catch (HttpException\ClientErrorResponseException $e) {
            //Might return
            //Client error response
            // [status code] 404
            // [reason phrase] Not Found
            // print_r($e->getMessage());
            return false;
        } catch (\Exception $e) {
            // print_r($e->getMessage());
            return false;
        }

        foreach ($channels['items'] as $channel) {
            if (!empty($channel['id'])) {
                return true;
            }
        }

        return false;
    }

    public function getUserChannelsId($maxResults = 5)
    {
        $params = array(
            'part' => 'id',
            'forUsername' => $this->account->get('youtube_remote_user_id'),
            'maxResults' => $maxResults,
            'key' => $this->key,
        );
        $command = $this->client->getCommand('ListChannels', $params);
        $response = $this->client->execute($command);

        return $response;
    }

    public function getUserChannels($maxResults = 50)
    {
        $params = array(
            'part' => 'contentDetails',
            'forUsername' => $this->account->get('youtube_remote_user_id'),
            'maxResults' => $maxResults,
            'key' => $this->key,
        );
        $command = $this->client->getCommand('ListChannels', $params);
        $response = $this->client->execute($command);

        return $response;
    }

    public function getPlaylistItems($playlistId, $maxResults = 40)
    {
        
        $params = array(
            'part' => 'snippet',
            'playlistId' => $playlistId,
            'maxResults' => $maxResults,
            'key' => $this->key,
        );

        $command = $this->client->getCommand('ListPlaylistItems', $params);
        $response = $this->client->execute($command);

        return $response;
    }

    public function getUserMedias($perpage = 40)
    {
        $medias = array();
        $channels = array();
        $playlist = 'uploads';

        //For now max Results is for each channel
        try {
            $channels = $this->getUserChannels();
        } catch (HttpException\ClientErrorResponseException $e) {
            //Might return
            //Client error response
            // [status code] 404
            // [reason phrase] Not Found
            $channels['items'] = array();
            print_r($e->getMessage());
        } catch (\Exception $e) {
            print_r($e->getMessage());
            // print_r($e->getRequest());
            $channels['items'] = array();
        }

        foreach ($channels['items'] as $channel) {
            if (!isset($channel['contentDetails']['relatedPlaylists'][$playlist])) {
                throw new \Exception("Unknown youtube channel related playlist: ".$playlist, 1);
            }

            $playlistId = $channel['contentDetails']['relatedPlaylists'][$playlist];
            $playlist = array();

            try {
                $playlist = $this->getPlaylistItems($playlistId, $perpage);
            } catch (HttpException\ClientErrorResponseException $e) {
                //Might return
                //Client error response
                // [status code] 404
                // [reason phrase] Not Found
                $playlist['items'] = array();
            }

            foreach ($playlist['items'] as $item) {
                $medias[] = $item;
            }
        }
        return $medias;
    }

    public function getUserAttachments()
    {
        $perpage = 40;
        $searchTerm = '';

        if (isset($_POST['query']['posts_per_page'])) {
            $perpage = absint($_POST['query']['posts_per_page']);
        }
        if (isset($_POST['query']['s'])) {
            $searchTerm = sanitize_text_field($_POST['query']['s']);
        }

        $medias = $this->getUserMedias($perpage);
        // $medias = $response->getAll();

        $attachments = array();

        foreach ($medias as $i => $media) {
            $remoteMedia = new RemoteMedia($media);
            $remoteMedia->setAccount($this->getAccount());
            $attachments[$i] = $remoteMedia->toMediaManagerAttachment();
        }
        unset($attachments[count($attachments)-1]);
        return $attachments;
    }
}
