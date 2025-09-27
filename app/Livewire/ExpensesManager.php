<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ExpensesManager extends Component
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
        'refreshExpenses' => '$refresh',
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
            Expense::findOrFail($this->editingId)->update($payload);
            session()->flash('status', 'Expense updated successfully.');
        } else {
            Expense::create($payload);
            session()->flash('status', 'Expense logged successfully.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit(int $expenseId): void
    {
        $expense = Expense::findOrFail($expenseId);

        $this->form = array_merge($this->formDefaults(), Arr::only($expense->toArray(), array_keys($this->formDefaults())));
        $this->isEditing = true;
        $this->editingId = $expense->id;
    }

    public function confirmDelete(int $expenseId): void
    {
        $this->confirmingDelete = $expenseId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Expense::findOrFail($this->confirmingDelete)->delete();

        session()->flash('status', 'Expense deleted successfully.');

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
            'expense_number' => $this->defaultExpenseNumber(),
            'expense_date' => now()->toDateString(),
            'category' => null,
            'amount' => 0,
            'payment_method' => null,
            'notes' => null,
        ];
    }

    protected function defaultExpenseNumber(): string
    {
        $next = (Expense::max('id') ?? 0) + 1;

        return 'EXP-' . now()->format('ymd') . '-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    protected function rules(): array
    {
        return [
            'form.expense_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('expenses', 'expense_number')->ignore($this->editingId),
            ],
            'form.expense_date' => ['required', 'date'],
            'form.category' => ['nullable', 'string', 'max:120'],
            'form.amount' => ['required', 'numeric', 'min:0'],
            'form.payment_method' => ['nullable', 'string', 'max:120'],
            'form.notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function preparePayload(array $data): array
    {
        $data['expense_number'] = strtoupper($data['expense_number']);
        $data['amount'] = round((float) $data['amount'], 2);

        return Arr::map($data, fn ($value) => $value === '' ? null : $value);
    }

    public function render()
    {
        $expenses = Expense::query()
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('expense_number', 'like', '%' . $this->search . '%')
                        ->orWhere('category', 'like', '%' . $this->search . '%')
                        ->orWhere('payment_method', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('expense_date')
            ->paginate($this->perPage);

        return view('livewire.expenses-manager', [
            'expenses' => $expenses,
        ])->layout('layouts.app', [
            'title' => 'Expenses',
            'header' => 'Expenses',
            'subheader' => 'Log operational spending and keep an audit-ready ledger.',
        ]);
    }
}

