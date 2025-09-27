<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Quote;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class QuotesManager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?int $confirmingDelete = null;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    protected $listeners = [
        'refreshQuotes' => '$refresh',
    ];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules())['form'];
        $payload = $this->preparePayload($validated);

        if ($this->isEditing && $this->editingId) {
            Quote::findOrFail($this->editingId)->update($payload);
            session()->flash('status', 'Quote updated successfully.');
        } else {
            Quote::create($payload);
            session()->flash('status', 'Quote created successfully.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit(int $quoteId): void
    {
        $quote = Quote::findOrFail($quoteId);

        $this->form = array_merge($this->formDefaults(), Arr::only($quote->toArray(), array_keys($this->formDefaults())));
        $this->isEditing = true;
        $this->editingId = $quote->id;
    }

    public function confirmDelete(int $quoteId): void
    {
        $this->confirmingDelete = $quoteId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Quote::findOrFail($this->confirmingDelete)->delete();

        session()->flash('status', 'Quote deleted successfully.');

        $this->resetForm();
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->form = $this->formDefaults();
        $this->isEditing = false;
        $this->editingId = null;
        $this->confirmingDelete = null;
    }

    protected function formDefaults(): array
    {
        return [
            'quote_number' => $this->defaultQuoteNumber(),
            'customer_id' => null,
            'quote_date' => now()->toDateString(),
            'expiry_date' => null,
            'status' => array_key_first($this->statusOptions()),
            'total_amount' => 0,
            'notes' => null,
        ];
    }

    protected function defaultQuoteNumber(): string
    {
        $next = (Quote::max('id') ?? 0) + 1;

        return 'QUO-' . now()->format('ymd') . '-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    protected function rules(): array
    {
        return [
            'form.quote_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('quotes', 'quote_number')->ignore($this->editingId),
            ],
            'form.customer_id' => ['nullable', 'exists:customers,id'],
            'form.quote_date' => ['required', 'date'],
            'form.expiry_date' => ['nullable', 'date', 'after_or_equal:form.quote_date'],
            'form.status' => ['required', Rule::in(array_keys($this->statusOptions()))],
            'form.total_amount' => ['required', 'numeric', 'min:0'],
            'form.notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function preparePayload(array $data): array
    {
        $data['quote_number'] = strtoupper($data['quote_number']);
        $data['total_amount'] = round((float) $data['total_amount'], 2);

        return Arr::map($data, fn ($value) => $value === '' ? null : $value);
    }

    protected function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'sent' => 'Sent',
            'accepted' => 'Accepted',
            'declined' => 'Declined',
        ];
    }

    public function render()
    {
        $quotes = Quote::query()
            ->with('customer:id,name')
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('quote_number', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest('quote_date')
            ->paginate($this->perPage);

        $customers = Customer::orderBy('name')->get(['id', 'name']);

        return view('livewire.quotes-manager', [
            'quotes' => $quotes,
            'customers' => $customers,
            'statusOptions' => $this->statusOptions(),
        ])->layout('layouts.app', [
            'title' => 'Quotes',
            'header' => 'Quotes',
            'subheader' => 'Draft, send, and track customer quotations across their lifecycle.',
        ]);
    }
}

