<?php

declare(strict_types=1);

namespace Cortex\Bookings\Http\Requests\Adminarea;

use Carbon\Carbon;
use Rinvex\Support\Traits\Escaper;
use Illuminate\Foundation\Http\FormRequest;

class BookingFormRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Calculate price
        $endsAt = new Carbon($data['ends_at']);
        $startsAt = new Carbon($data['starts_at']);
        $service = app('cortex.bookings.service')->find($this->get('service_id'));
        [$price, $formula, $currency] = app('cortex.bookings.service_booking')->calculatePrice($service, $startsAt, $endsAt);

        // Fill missing fields
        $data['ends_at'] = $endsAt;
        $data['starts_at'] = $startsAt;
        $data['customer_type'] = 'member';
        $data['bookable_type'] = 'service';
        $data['currency'] = $currency;
        $data['formula'] = $formula;
        $data['price'] = $price;

        $this->replace($data);
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        // Sanitize input data before submission
        $this->replace($this->escape($this->all()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $bookableBooking = $this->route('booking') ?? app('cortex.bookings.service_booking');
        $bookableBooking->updateRulesUniques();

        return $bookableBooking->getRules();
    }
}
