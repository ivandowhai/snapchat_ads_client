<?php

namespace Snapchat;

use Snapchat\Entities\Ad;
use Snapchat\Entities\Campaign;
use Snapchat\Entities\Creative;
use Snapchat\Entities\Media;
use Snapchat\Entities\Pixel;
use Snapchat\Entities\PixelStatsQuery;
use Snapchat\Entities\PriceQuoteQuery;
use Snapchat\Entities\Stats;
use Snapchat\Entities\StatsQuery;
use Snapchat\Entities\Targeting;

class SnapchatAdsAPI
{

    /**
     * @var HTTPClientInterface
     */
    private $client;

    const MICRO_CURRENCY_COEFFICIENT = 1000000;


    /**
     * SnapchatAdsAPI constructor.
     * @param HTTPClientInterface $client
     */
    public function __construct(HTTPClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param float $value
     * @return float
     */
    public function convertToMicroCurrency(float $value): float
    {
        return $value * self::MICRO_CURRENCY_COEFFICIENT;
    }


    /**
     * @param array $list
     * @param string $entity
     * @param string $class
     * @return array
     * @throws \Exception
     */
    private function parseRequest(array $list, string $entity, string $class) : array
    {
        if (!class_exists($class)) {
            throw new \Exception($class . ' class does not exists');
        }

        $array = [];
        foreach ($list[$entity] as $item) {
            $array[] = new $class($item);
        }

        return $array;
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAuthUser()
    {
        $result = $this->client->get('/me');
        if (isset($result['me'])) {
            return $result['me'];
        }
        throw new \Exception('Error');
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function getAllOrganizations(): array 
    {
        $result = $this->client->get('/me/organizations');
        if ($this->client->checkResponse($result)) {
            return $result;
        }
        throw new \Exception('Error');
    }


    /**
     * @param string $id
     * @return array
     * @throws \Exception
     */
    public function getOrganization(string $id): array
    {
        $result = $this->client->get("/organizations/$id");
        if ($this->client->checkResponse($result)) {
            return $result;
        }
        throw new \Exception('Error');
    }

    /**
     * @param string $organizationId
     * @return array
     * @throws \Exception
     */
    public function getAllAccounts(string $organizationId): array
    {
        $result = $this->client->get("/organizations/$organizationId/adaccounts");
        if ($this->client->checkResponse($result)) {
            return $result;
        }
        throw new \Exception('Error');
    }

    /**
     * @param string $id
     * @return array
     * @throws \Exception
     */
    public function getAccount(string $id): array
    {
        $result = $this->client->get("/adaccounts/$id");
        if ($this->client->checkResponse($result)) {
            return $result;
        }
        throw new \Exception('Error');
    }

    /**
     * You can use Campaign and GEOFilterCampaign in $campaigns
     *
     * @param array $campaigns
     * @param string $ad_account_id
     * @return array
     * @throws \Exception
     */
    public function createCampaigns(array $campaigns, string $ad_account_id) : array
    {
        $result = $this->client->post("/adaccounts/{$ad_account_id}/campaigns",
            ['campaigns' => $campaigns]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['campaigns'], 'campaign', 'Campaign');
        }
        // TODO: errors
        throw new \Exception('Error! Campaign does not create');
    }

    /**
     * You can use Campaign and GEOFilterCampaign in $campaigns
     * If you use GEOFilterCampaign you may update only "name" and "end_time"
     *
     * @param array $campaigns
     * @param string $ad_account_id
     * @return array
     * @throws \Exception
     */
    public function updateCampaigns(array $campaigns, string $ad_account_id) : array
    {
        $result = $this->client->put("/adaccounts/{$ad_account_id}/campaigns",
            ['campaigns' => $campaigns]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['campaigns'], 'campaign', 'Campaign');
        }
        throw new \Exception('Error! Campaign does not create');
    }

    /**
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function getAllCampaigns(string $adAccountId) : array
    {
        $result = $this->client->get("/adaccounts/{$adAccountId}/campaigns");
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['campaigns'], 'campaign', 'Campaign');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param $id
     * @return Campaign
     * @throws \Exception
     */
    public function getCampaign($id) : Campaign
    {
        $result = $this->client->get("/campaigns/$id");
        if ($this->client->checkResponse($result)) {
            return new Campaign($result['campaigns'][0]['campaign']);
        }
        throw new \Exception('Error');
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteCampaign(string $id) : bool
    {
        $result = $this->client->delete("/campaigns/$id");
        return $this->client->checkResponse($result);
    }

    /**
     * You can use AdSquad and GEOFilterAdSquad in $adSquads
     *
     * @param array $adSquads
     * @param string $campaignId
     * @return array
     * @throws \Exception
     */
    public function createAdSquads(array $adSquads, $campaignId) : array
    {
        $result = $this->client->post("/campaigns/{$campaignId}/adsquads",
            ['adsquads' => $adSquads]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['adsquads'], 'adsquad', 'AdSquad');
        }
        throw new \Exception('Error! AdSquad does not create');
    }

    /**
     * You can use AdSquad and GEOFilterAdSquad in $adSquads
     * If you use GEOFilterAdSquad you may update only "name"
     *
     * @param array $adSquads
     * @param string $campaignId
     * @return array
     * @throws \Exception
     */
    public function updateAdSquads(array $adSquads, string $campaignId) : array
    {
        $result = $this->client->put("/campaigns/{$campaignId}/adsquads",
            ['adsquads' => $adSquads]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['adsquads'], 'adsquad', 'AdSquad');
        }
        throw new \Exception('Error! AdSquad does not create');
    }

    /**
     * @param string $campaignId
     * @return array
     * @throws \Exception
     */
    public function getAllAdSquadsUnderCampaign(string $campaignId): array 
    {
        $result = $this->client->get("/campaigns/{$campaignId}/adsquads");
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['adsquads'], 'adsquad', 'AdSquad');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function getAllAdSquadsUnderAdAccount(string $adAccountId): array 
    {
        $result = $this->client->get("/adaccounts/{$adAccountId}/adsquads");
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['adsquads'], 'adsquad', 'AdSquad');
        }
        throw new \Exception('Error!');
    }


    /**
     * @param string $id
     * @return bool
     */
    public function deleteAdSquad(string $id) : bool
    {
        $result = $this->client->delete("/adsquads/$id");
        return $this->client->checkResponse($result);
    }

    /**
     * You can use Ad and GEOFilterAd in $ads
     *
     * @param array $ads
     * @param string $adSquadId
     * @return array
     * @throws \Exception
     */
    public function createAds(array $ads, string $adSquadId): array 
    {
        $result = $this->client->post("/adsquads/{$adSquadId}/ads",
            ['ads' => $ads]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['ads'], 'ad', 'Ad');
        }
        throw new \Exception('Error!');
    }

    /**
     * You can use Ad and GEOFilterAd in $ads
     * If you use GEOFilterAdSquad you may update only "name" and "status" to "PAUSED",
     *      (once PAUSED, cannot be updated back to ACTIVE again)
     *
     * @param array $ads
     * @param string $adSquadId
     * @return array
     * @throws \Exception
     */
    public function updateAds(array $ads, string $adSquadId): array 
    {
        $result = $this->client->put("/adsquads/{$adSquadId}/ads",
            ['ads' => $ads]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['ads'], 'ad', 'Ad');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $adSquadId
     * @return array
     * @throws \Exception
     */
    public function getAllAdsAnAdSquad(string $adSquadId) : array
    {
        $result = $this->client->get("/adsquads/{$adSquadId}/ads");
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['ads'], 'ad', 'Ad');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function getAllAdsAnAdAccount(string $adAccountId) : array
    {
        $result = $this->client->get("/adaccounts/{$adAccountId}/ads");
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['ads'], 'ad', 'Ad');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $id
     * @return Ad
     * @throws \Exception
     */
    public function getAd(string $id) : Ad
    {
        $result = $this->client->get("/ads/$id");
        if ($this->client->checkResponse($result)) {
            return new Ad($result['ads'][0]['ad']);
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteAd(string $id) : bool
    {
        $result = $this->client->delete("/ads/$id");
        return $this->client->checkResponse($result);
    }

    /**
     * @param array $media
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function createMedia(array $media, string $adAccountId) : array
    {
        $result = $this->client->post("/adaccounts/{$adAccountId}/media",
            ['media' => $media]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['media'], 'media', 'Media');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param Media $media
     * @param string $pathToFile
     * @param string $fileName
     * @return array
     */
    public function uploadMediaVideo(Media $media, string $pathToFile, string $fileName): array
    {
        if ($media->type !== Media::TYPE_VIDEO) {
            return ['errors' => 'Wrong media type'];
        }

        $result = $this->client->upload("/media/{$media->id}/upload", $pathToFile, $fileName);
        return $this->client->checkResponse($result);
    }


    /**
     * @param Media $media
     * @param string $pathToFile
     * @param string $fileName
     * @return array|bool
     */
    public function uploadMediaImage(Media $media, string $pathToFile, string $fileName)
    {
        if ($errors = $media->validateImageForUpload($fileName)) {
            return $errors;
        }

        $result = $this->client->upload("/media/{$media->id}/upload", $pathToFile, $fileName);
        return $this->client->checkResponse($result);
    }

    /**
     * TODO: uploads parts
     *
     * @param string $id
     * @return Media
     * @throws \Exception
     */
    public function getMedia(string $id) : Media
    {
        $result = $this->client->get("/media/$id");
        if ($this->client->checkResponse($result)) {
            return new Media($result['media'][0]['media']);
        }
        throw new \Exception('Error!');
    }

    /**
     * @param array $creatives
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function createCreatives(array $creatives, string $adAccountId) : array
    {
        $result = $this->client->post("/adaccounts/{$adAccountId}/creatives",
            ['creatives' => $creatives]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['creatives'], 'creative', 'Creative');
        }
        throw new \Exception('Error!');
    }


    /**
     * TODO: Creative Attachments
     *
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function getAllCreatives(string $adAccountId) :array
    {
        $result = $this->client->get("/adaccounts/{$adAccountId}/creatives");
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['creatives'], 'creative', 'Creative');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $id
     * @return Creative
     * @throws \Exception
     */
    public function getCreative(string $id) : Creative
    {
        $result = $this->client->get("/creatives/$id");
        if ($this->client->checkResponse($result)) {
            return new Ad($result['creatives'][0]['creative']);
        }
        throw new \Exception('Error!');
    }

    /**
     * @param Creative $creative
     * @return array
     * @throws \Exception
     */
    public function getSnapcode(Creative $creative)
    {
        $result = $this->client->get("/creatives/{$creative->id}/snapcode");
        if ($this->client->checkResponse($result)) {
            return [
                'creative_id' => $result['creative_id'],
                'expires_at' => $result['expires_at'],
                'snapcode_link' => $result['snapcode_link']
            ];
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $id
     * @param string $type
     * @param StatsQuery $statsQuery
     * @return Stats
     * @throws \Exception
     */
    public function getStats(string $id, string $type, StatsQuery $statsQuery): Stats
    {
        if (!Stats::validateType($type)) {
            throw new \Exception('Wrong type');
        }

        $endpoint = Stats::getEndpoint($type);
        $result = $this->client->get("/{$endpoint}/{$id}/stats", $statsQuery);
        if ($this->client->checkResponse($result)) {
            return new Stats($result['total_stats'][0]['total_stat']['stats']);
        }
        throw new \Exception('Error!');
    }

    /**
     * Geofilter Ad Squads do not have bids and the price is decided by the API and set on creation.
     * The /geo_filter_quote endpoint can be used to get the price quote for the intended dates and specified
     * targeting spec before creating the Ad Squad to understand what the cost will be.
     *
     * @param string $accountId
     * @param PriceQuoteQuery $priceQuoteQuery
     * @return array
     * @throws \Exception
     */
    public function getGEOFilterPriceQuote(string $accountId, PriceQuoteQuery $priceQuoteQuery) : array
    {
        $result = $this->client->post("/adaccounts/{$accountId}/geo_filter_quote", $priceQuoteQuery);
        if ($this->client->checkResponse($result)) {
            return $result['geo_filter_quote'];
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function getPixelsInAdAccount(string $adAccountId) : array
    {
        $result = $this->client->get("/adaccounts/{$adAccountId}/pixels");
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['pixels'], 'pixel', 'Pixel');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $id
     * @return Pixel
     * @throws \Exception
     */
    public function getPixel(string $id) : Pixel
    {
        $result = $this->client->get("/pixels/$id");
        if ($this->client->checkResponse($result)) {
            return new Pixel($result['pixels'][0]['pixel']);
        }
        throw new \Exception('Error!');
    }

    /**
     * You may update only "name"
     *
     * @param array $pixels
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function updatePixels(array $pixels, string $adAccountId) : array
    {
        $result = $this->client->post("/adaccounts/{$adAccountId}/pixels",
            ['pixels' => $pixels]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['pixels'], 'pixel', 'Pixel');
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $pixelId
     * @return array
     * @throws \Exception
     */
    public function getPixelDomains(string $pixelId) : array
    {
        $result = $this->client->get("/pixels/{$pixelId}/domains/stats");
        if ($this->client->checkResponse($result)) {
            return $result['timeseries_stats'][0]['timeseries_stat']['domains'];
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $pixelId
     * @param PixelStatsQuery $pixelStatsQuery
     * @return mixed
     * @throws \Exception
     */
    public function getPixelStatsForSpecificDomain(string $pixelId, PixelStatsQuery $pixelStatsQuery)
    {
        $result = $this->client->get("/pixels/{$pixelId}/stats", $pixelStatsQuery);
        if ($this->client->checkResponse($result)) {
            return $result['timeseries_stats'][0]['timeseries_stat']['timeseries'];
        }
        throw new \Exception('Error!');
    }

    /**
     * See the self::$targetingParts for $part and $subpart, see self::$difficultParts for $advancedOption
     * You can use "cursor" and "limit" query params in "postal_code" endpoint
     *
     * @param string $part
     * @param string $subpart
     * @param string $advancedOption
     * @param array $queryParams
     * @return mixed
     * @throws \Exception
     */
    public function getTargetingSpecs(string $part, string $subpart, string $advancedOption = '', array $queryParams = [])
    {
        Targeting::validateTargetingLinkParts($part, $subpart, $advancedOption);

        if (array_key_exists($subpart, Targeting::getDifficultParts())) {
            $url = "/targeting/{$part}/{$advancedOption}/{$subpart}";
        } else {
            $url = "/targeting/{$part}/{$subpart}";
        }

        $result = $this->client->get($url, $queryParams);
        if ($this->client->checkResponse($result)) {
            return $result['targeting_dimensions'];
        }
        throw new \Exception('Error!');
    }


    /**
     * @param string $countryCode
     * @return array
     * @throws \Exception
     */
    public function getTargetingTypeSupport(string $countryCode): array
    {
        $result = $this->client->get("/targeting/options/{$countryCode}");
        if ($this->client->checkResponse($result)) {
            return $result['targeting_option'];
        }
        throw new \Exception('Error!');
    }


    /**
     * @param array $segments
     * @param string $adAccountId
     * @return array
     * @throws \Exception
     */
    public function createLoakalikeSegments(array $segments, string $adAccountId) : array
    {
        $result = $this->client->post("/adaccounts/{$adAccountId}/segments",
            ['segments' => $segments]);
        if ($this->client->checkResponse($result)) {
            return $this->parseRequest($result['segments'], 'segment', 'Lookalike');
        }
        throw new \Exception('Error!');
    }


    /**
     * @param string $adSquadId
     * @return mixed
     * @throws \Exception
     */
    public function getAudienceSizeByAdSquad(string $adSquadId)
    {
        $result = $this->client->get("/adsquads/{$adSquadId}/audience_size");
        if ($this->client->checkResponse($result)) {
            return $result['audience_size'];
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $adAccountId
     * @return mixed
     * @throws \Exception
     */
    public function getAudienceSizeByTargetingSpec(string $adAccountId)
    {
        $result = $this->client->get("/adaccounts/{$adAccountId}/audience_size");
        if ($this->client->checkResponse($result)) {
            return $result['audience_size'];
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $adSquadId
     * @return mixed
     * @throws \Exception
     */
    public function getBidEstimateByAdSquad(string $adSquadId)
    {
        $result = $this->client->get("/adsquads/{$adSquadId}/bid_estimate");
        if ($this->client->checkResponse($result)) {
            return $result['bid_estimate'];
        }
        throw new \Exception('Error!');
    }

    /**
     * @param string $adAccountId
     * @param Targeting $targeting
     * @param string $optimization_goal
     * @return mixed
     * @throws \Exception
     */
    public function getBidEstimateByTargetingSpec(string $adAccountId, Targeting $targeting, string $optimization_goal)
    {
        $result = $this->client->post("/adaccounts/{$adAccountId}/bid_estimate",
            ['optimization_goal' => $optimization_goal, 'targeting' => $targeting]);
        if ($this->client->checkResponse($result)) {
            return $result['audience_size'];
        }
        throw new \Exception('Error!');
    }

}