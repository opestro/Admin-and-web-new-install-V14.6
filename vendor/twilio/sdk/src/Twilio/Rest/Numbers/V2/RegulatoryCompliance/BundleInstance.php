<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Numbers
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Numbers\V2\RegulatoryCompliance;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;
use Twilio\Deserialize;
use Twilio\Rest\Numbers\V2\RegulatoryCompliance\Bundle\ReplaceItemsList;
use Twilio\Rest\Numbers\V2\RegulatoryCompliance\Bundle\EvaluationList;
use Twilio\Rest\Numbers\V2\RegulatoryCompliance\Bundle\BundleCopyList;
use Twilio\Rest\Numbers\V2\RegulatoryCompliance\Bundle\ItemAssignmentList;


/**
 * @property string|null $sid
 * @property string|null $accountSid
 * @property string|null $regulationSid
 * @property string|null $friendlyName
 * @property string $status
 * @property \DateTime|null $validUntil
 * @property string|null $email
 * @property string|null $statusCallback
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property string|null $url
 * @property array|null $links
 */
class BundleInstance extends InstanceResource
{
    protected $_replaceItems;
    protected $_evaluations;
    protected $_bundleCopies;
    protected $_itemAssignments;

    /**
     * Initialize the BundleInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $sid The unique string that we created to identify the Bundle resource.
     */
    public function __construct(Version $version, array $payload, string $sid = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'sid' => Values::array_get($payload, 'sid'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'regulationSid' => Values::array_get($payload, 'regulation_sid'),
            'friendlyName' => Values::array_get($payload, 'friendly_name'),
            'status' => Values::array_get($payload, 'status'),
            'validUntil' => Deserialize::dateTime(Values::array_get($payload, 'valid_until')),
            'email' => Values::array_get($payload, 'email'),
            'statusCallback' => Values::array_get($payload, 'status_callback'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
            'url' => Values::array_get($payload, 'url'),
            'links' => Values::array_get($payload, 'links'),
        ];

        $this->solution = ['sid' => $sid ?: $this->properties['sid'], ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return BundleContext Context for this BundleInstance
     */
    protected function proxy(): BundleContext
    {
        if (!$this->context) {
            $this->context = new BundleContext(
                $this->version,
                $this->solution['sid']
            );
        }

        return $this->context;
    }

    /**
     * Delete the BundleInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(): bool
    {

        return $this->proxy()->delete();
    }

    /**
     * Fetch the BundleInstance
     *
     * @return BundleInstance Fetched BundleInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): BundleInstance
    {

        return $this->proxy()->fetch();
    }

    /**
     * Update the BundleInstance
     *
     * @param array|Options $options Optional Arguments
     * @return BundleInstance Updated BundleInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []): BundleInstance
    {

        return $this->proxy()->update($options);
    }

    /**
     * Access the replaceItems
     */
    protected function getReplaceItems(): ReplaceItemsList
    {
        return $this->proxy()->replaceItems;
    }

    /**
     * Access the evaluations
     */
    protected function getEvaluations(): EvaluationList
    {
        return $this->proxy()->evaluations;
    }

    /**
     * Access the bundleCopies
     */
    protected function getBundleCopies(): BundleCopyList
    {
        return $this->proxy()->bundleCopies;
    }

    /**
     * Access the itemAssignments
     */
    protected function getItemAssignments(): ItemAssignmentList
    {
        return $this->proxy()->itemAssignments;
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Numbers.V2.BundleInstance ' . \implode(' ', $context) . ']';
    }
}

