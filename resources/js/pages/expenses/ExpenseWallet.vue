<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { LoaderCircle, Pen, PenBoxIcon, Plus, TrashIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expense Wallet',
        href: '/expense-wallets',
    },
];

const props = defineProps({
    expenseWallets: Object,
    bankTypes: Object,
    expenseCategories: Object,
    loans: Object,
    loanTypes: Object,
    expenseDetails: Object,
});

const tableHeads = ['ID', 'Amount', 'Bank Type', 'Expense Category', 'Expense Detail', 'Created At', 'Action'];

const form = useForm({
    amount: 0,
    bank_type_id: '',
    bank_type_others: '',
    expense_category_id: '',
    expense_detail_id: '',
    expense_category_others: '',
    expense_detail_others: '',
    loan_id: '',
    loan_type_id: '',
    remaining_balance: 0,
});

const modalOpen = ref(false);

const editModalOpen = ref(false);

const selectedExpenseWallet: any = ref(null);

const deleteAlertDialogOpen: any = ref(false);

const selectedToDelete: any = ref(null);

function submit() {
    form.post(route('expenses.store'), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Created!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
            });

            form.reset();

            modalOpen.value = false;
        },
    });
}

const deleteExpenseWallet = (id: number) => {
    form.delete(route('expenses.destroy', id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Deleted!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
            });

            deleteAlertDialogOpen.value = false;
        },
    });
};
const handleEdit = (expenseWallet: any) => {
    form.amount = expenseWallet.amount;
    form.bank_type_id = expenseWallet.bank_type_id;
    form.expense_category_id = expenseWallet.expense_category_id;
    form.expense_detail_id = expenseWallet.expense_detail_id;
    form.loan_id = expenseWallet.loan_id;
    form.loan_type_id = expenseWallet.loan_type_id;
    selectedExpenseWallet.value = expenseWallet;
    form.errors = {};
    editModalOpen.value = true;
};

const handleUpdate = () => {
    form.patch(route('expenses.update', selectedExpenseWallet.value.id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            editModalOpen.value = false;

            toast('Updated!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
            });

            form.reset();
        },
    });
};

const handleOpenModal = () => {
    form.reset();
    form.errors = {};
    modalOpen.value = true;
};

function resetIfOthers(fieldItem: string, fieldOthers: string) {
    watch(
        () => (form as any)[fieldItem],
        (newValue) => {
            if (newValue !== 'others') {
                (form as any)[fieldOthers] = '';
                (form as any).errors[fieldOthers] = '';
            }
        },
    );
}

watch(
    () => [form.loan_id, form.expense_category_others, form.bank_type_id],
    () => {
        if (form.expense_category_others.toLowerCase() === 'loans') {
            const loan = props.loans?.find((l: any) => l.id === form.loan_id);
            form.remaining_balance = Number(loan?.amount) - Number(loan?.expenses_sum_amount);
        } else {
            const bankType = props.bankTypes?.find((b: any) => b.id === form.bank_type_id);
            form.remaining_balance =
                Number(bankType?.wallets_sum_amount) + (Number(bankType?.loans_sum_amount) - Number(bankType?.expenses_sum_amount));
        }
    },
    { immediate: true },
);

resetIfOthers('bank_type_id', 'bank_type_others');
resetIfOthers('expense_category_id', 'loan_id');
resetIfOthers('expense_category_id', 'loan_type_id');

watch(
    () => form.expense_category_id,
    (newId) => {
        const expenseCategory = props?.expenseCategories?.find((expenseCategory: any) => expenseCategory.id === newId);
        form.expense_category_others = expenseCategory?.name ?? '';
    },
);

const handleAlertDialogOpen = (item: number) => {
    selectedToDelete.value = item;
    deleteAlertDialogOpen.value = true;
};
</script>

<template>
    <Head title="Expense Wallet" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <div class="w-full">
                <Dialog v-model:open="modalOpen">
                    <Button class="float-end bg-blue-500 text-white hover:bg-blue-600" @click="handleOpenModal"><Plus /> Add Expense Wallet</Button>
                    <DialogContent class="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Add Expense Wallet</DialogTitle>
                        </DialogHeader>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div class="space-y-2">
                                <Label for="amount" class="text-right"> Amount </Label>
                                <Input
                                    type="number"
                                    id="amount"
                                    :class="form.errors.amount && 'border-red-500'"
                                    placeholder="Enter Amount"
                                    v-model="form.amount"
                                    step="any"
                                />
                                <InputError :message="form.errors.amount" />
                            </div>
                            <div class="space-y-2">
                                <Label for="expense_category" class="text-right"> Expense Category </Label>
                                <Select v-model="form.expense_category_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select expense category" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="(expenseCategory, index) in expenseCategories" :key="index" :value="expenseCategory.id">
                                            {{ expenseCategory.name }}
                                        </SelectItem>
                                        <!-- <SelectItem value="others"> Others </SelectItem> -->
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.expense_category_id" />
                            </div>
                            <div class="space-y-2" v-if="form.expense_category_id === 'others'">
                                <Label for="bank_type" class="text-right"> Expense Category Others </Label>
                                <Input
                                    type="text"
                                    id="expense_category_others"
                                    :class="form.errors.expense_category_others && 'border-red-500'"
                                    placeholder="Enter Expense Category Others"
                                    v-model="form.expense_category_others"
                                />
                                <InputError :message="form.errors.expense_category_others" />
                            </div>
                            <div class="space-y-2" v-if="form.expense_category_others.toLowerCase() === 'loans'">
                                <Label for="loan_type_id" class="text-right"> Loan Type </Label>
                                <Select v-model="form.loan_type_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue :placeholder="loanTypes?.length > 0 ? 'Select loan type' : 'No loan type available'" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="(loanType, index) in loanTypes" :key="index" :value="loanType.id">
                                            {{ loanType.name }}
                                        </SelectItem>
                                        <SelectItem value="No loan type available " disabled v-if="loanTypes?.length === 0">
                                            No loan type available
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.loan_type_id" />
                            </div>
                            <div class="space-y-2">
                                <Label for="expense_detail" class="text-right"> Expense Detail </Label>
                                <Select v-model="form.expense_detail_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select expense detail" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="(expenseDetail, index) in expenseDetails" :key="index" :value="expenseDetail.id">
                                            {{ expenseDetail.name }}
                                        </SelectItem>
                                        <SelectItem value="others"> Others </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.expense_detail_id" />
                            </div>
                            <div class="space-y-2" v-if="form.expense_detail_id === 'others'">
                                <Label for="bank_type" class="text-right"> Expense Detail Others </Label>
                                <Input
                                    type="text"
                                    id="expense_detail_others"
                                    :class="form.errors.expense_detail_others && 'border-red-500'"
                                    placeholder="Enter Expense Detail Others"
                                    v-model="form.expense_detail_others"
                                />
                                <InputError :message="form.errors.expense_detail_others" />
                            </div>
                            <div class="space-y-2" v-if="form.expense_category_others.toLowerCase() === 'loans'">
                                <Label for="loan_id" class="text-right"> Loan Availed </Label>
                                <Select v-model="form.loan_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue :placeholder="loans?.length > 0 ? 'Select loan availed' : 'No loan availed available'" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="(loan, index) in loans?.filter(
                                                (loan: any) => loan?.loan_type_id === form?.loan_type_id && loan?.amount > loan?.expenses_sum_amount,
                                            )"
                                            :key="index"
                                            :value="loan.id"
                                        >
                                            {{ loan.bank_type.name }} - (Remaining:
                                            {{
                                                Number(Number(loan.amount) - Number(loan.expenses_sum_amount)).toLocaleString('en-US', {
                                                    style: 'currency',
                                                    currency: 'PHP',
                                                })
                                            }})
                                        </SelectItem>
                                        <SelectItem
                                            v-if="
                                                loans?.filter(
                                                    (loan: any) =>
                                                        loan?.loan_type_id === form?.loan_type_id && loan?.amount > loan?.expenses_sum_amount,
                                                )?.length === 0
                                            "
                                            disabled
                                            value="No loan availed available"
                                            >No loan availed available</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.loan_id" />
                            </div>
                            <div class="space-y-2">
                                <Label for="bank_type" class="text-right"> Bank Type </Label>
                                <Select v-model="form.bank_type_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select bank type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="(bankType, index) in bankTypes" :key="index" :value="bankType.id">
                                            {{ bankType.name }} (Remaining balance:
                                            {{
                                                Number(
                                                    Number(bankType?.wallets_sum_amount) +
                                                        (Number(bankType?.loans_sum_amount) - Number(bankType?.expenses_sum_amount)),
                                                )?.toLocaleString('en-US', { style: 'currency', currency: 'PHP' })
                                            }})
                                        </SelectItem>
                                        <!-- <SelectItem value="others"> Others </SelectItem> -->
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.bank_type_id" />
                            </div>
                            <div class="space-y-2" v-if="form.bank_type_id === 'others'">
                                <Label for="bank_type" class="text-right"> Bank Type Others </Label>
                                <Input
                                    type="text"
                                    id="bank_type_others"
                                    :class="form.errors.bank_type_others && 'border-red-500'"
                                    placeholder="Enter Bank Type Others"
                                    v-model="form.bank_type_others"
                                />
                                <InputError :message="form.errors.bank_type_others" />
                            </div>

                            <DialogFooter>
                                <Button type="submit" :disabled="form.processing">
                                    <span v-if="form.processing" class="flex items-center gap-1"
                                        ><LoaderCircle class="h-4 w-4 animate-spin" /> Adding...
                                    </span>
                                    <span class="flex items-center gap-1" v-else><Plus /> Add</span>
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="(item, id) in tableHeads" :key="id">{{ item }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(expenseWallet, index) in expenseWallets" :key="index">
                        <TableCell> {{ expenseWallet.id }}</TableCell>
                        <TableCell> {{ Number(expenseWallet.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }} </TableCell>
                        <TableCell> {{ expenseWallet?.bank_type?.name }}</TableCell>
                        <TableCell>
                            <p>{{ expenseWallet?.expense_category?.name }}</p>
                            <p class="text-gray-500 capitalize" v-if="expenseWallet?.loan_type">Loan type: {{ expenseWallet?.loan_type?.name }}</p>
                            <p class="text-gray-500 capitalize" v-if="expenseWallet?.loan">
                                Loan expensed: {{ expenseWallet?.loan?.loan_type?.name }}
                            </p>
                            <p class="text-gray-500 capitalize" v-if="expenseWallet?.loan">Loan bank: {{ expenseWallet?.loan?.bank_type?.name }}</p>
                        </TableCell>
                        <TableCell>
                            {{ expenseWallet?.expense_detail?.name }}
                        </TableCell>
                        <TableCell> {{ format(expenseWallet.created_at, 'MMM dd, yyyy hh:mm a') }}</TableCell>
                        <TableCell class="flex gap-2">
                            <Button class="bg-blue-500 text-white hover:bg-blue-600" @click="handleEdit(expenseWallet)"><PenBoxIcon /></Button>
                            <Button @click="handleAlertDialogOpen(expenseWallet)" class="bg-red-500 text-white hover:bg-red-600"
                                ><TrashIcon />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="expenseWallets?.length === 0">
                        <TableCell colspan="6" class="text-center">No expense wallet found.</TableCell>
                    </TableRow>
                    <Dialog v-model:open="editModalOpen">
                        <DialogContent class="sm:max-w-[425px]">
                            <DialogHeader>
                                <DialogTitle
                                    >Editing {{ selectedExpenseWallet?.amount }} ({{ selectedExpenseWallet?.bank_type?.name }} -
                                    {{ selectedExpenseWallet?.expense_category?.name }})...</DialogTitle
                                >
                            </DialogHeader>
                            <form @submit.prevent="handleUpdate" class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="amount" class="text-right"> Amount </Label>
                                    <Input
                                        type="number"
                                        id="amount"
                                        :class="form.errors.amount && 'border-red-500'"
                                        placeholder="Enter Amount"
                                        v-model="form.amount"
                                        step="any"
                                    />
                                    <InputError :message="form.errors.amount" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="expense_category" class="text-right"> Expense Category </Label>
                                    <Select v-model="form.expense_category_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select expense category" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="(expenseCategory, index) in expenseCategories"
                                                :key="index"
                                                :value="expenseCategory.id"
                                            >
                                                {{ expenseCategory.name }}
                                            </SelectItem>
                                            <!-- <SelectItem value="others"> Others </SelectItem> -->
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.expense_category_id" />
                                </div>
                                <div class="space-y-2" v-if="form.expense_category_id === 'others'">
                                    <Label for="bank_type" class="text-right"> Expense Category Others </Label>
                                    <Input
                                        type="text"
                                        id="expense_category_others"
                                        :class="form.errors.expense_category_others && 'border-red-500'"
                                        placeholder="Enter Expense Category Others"
                                        v-model="form.expense_category_others"
                                    />
                                    <InputError :message="form.errors.expense_category_others" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="expense_detail" class="text-right"> Expense Detail </Label>
                                    <Select v-model="form.expense_detail_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select expense detail" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(expenseDetail, index) in expenseDetails" :key="index" :value="expenseDetail.id">
                                                {{ expenseDetail.name }}
                                            </SelectItem>
                                            <SelectItem value="others"> Others </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.expense_detail_id" />
                                </div>
                                <div class="space-y-2" v-if="form.expense_detail_id === 'others'">
                                    <Label for="bank_type" class="text-right"> Expense Detail Others </Label>
                                    <Input
                                        type="text"
                                        id="expense_detail_others"
                                        :class="form.errors.expense_detail_others && 'border-red-500'"
                                        placeholder="Enter Expense Detail Others"
                                        v-model="form.expense_detail_others"
                                    />
                                    <InputError :message="form.errors.expense_detail_others" />
                                </div>
                                <div class="space-y-2" v-if="form.expense_category_others.toLowerCase() === 'loans'">
                                    <Label for="loan_type_id" class="text-right"> Loan Type </Label>
                                    <Select v-model="form.loan_type_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue :placeholder="loanTypes?.length > 0 ? 'Select loan type' : 'No loan type available'" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(loanType, index) in loanTypes" :key="index" :value="loanType.id">
                                                {{ loanType.name }}
                                            </SelectItem>
                                            <SelectItem value="No loan type available " disabled v-if="loanTypes?.length === 0">
                                                No loan type available
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.loan_type_id" />
                                </div>
                                <div class="space-y-2" v-if="form.expense_category_others.toLowerCase() === 'loans'">
                                    <Label for="loan_id" class="text-right"> Loan Availed </Label>
                                    <Select v-model="form.loan_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue :placeholder="loans?.length > 0 ? 'Select loan availed' : 'No loan availed available'" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="(loan, index) in loans?.filter(
                                                    (loan: any) =>
                                                        loan?.loan_type_id === form?.loan_type_id && loan?.amount > loan?.expenses_sum_amount,
                                                )"
                                                :key="index"
                                                :value="loan.id"
                                            >
                                                {{ loan.bank_type.name }} - ({{
                                                    Number(Number(loan.amount) - Number(loan.expenses_sum_amount)).toLocaleString('en-US', {
                                                        style: 'currency',
                                                        currency: 'PHP',
                                                    })
                                                }})
                                            </SelectItem>
                                            <SelectItem
                                                v-if="
                                                    loans?.filter(
                                                        (loan: any) =>
                                                            loan?.loan_type_id === form?.loan_type_id && loan?.amount > loan?.expenses_sum_amount,
                                                    )?.length === 0
                                                "
                                                disabled
                                                value="No loan availed available"
                                                >No loan availed available</SelectItem
                                            >
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.loan_id" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="bank_type" class="text-right"> Bank Type </Label>
                                    <Select v-model="form.bank_type_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select bank type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(bankType, index) in bankTypes" :key="index" :value="bankType.id">
                                                {{ bankType.name }} (Remaining balance:
                                                {{
                                                    Number(
                                                        Number(bankType?.wallets_sum_amount) +
                                                            (Number(bankType?.loans_sum_amount) - Number(bankType?.expenses_sum_amount)),
                                                    )?.toLocaleString('en-US', {
                                                        style: 'currency',
                                                        currency: 'PHP',
                                                    })
                                                }})
                                            </SelectItem>
                                            <!-- <SelectItem value="others"> Others </SelectItem> -->
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.bank_type_id" />
                                </div>
                                <div class="space-y-2" v-if="form.bank_type_id === 'others'">
                                    <Label for="bank_type" class="text-right"> Bank Type Others </Label>
                                    <Input
                                        type="text"
                                        id="bank_type_others"
                                        :class="form.errors.bank_type_others && 'border-red-500'"
                                        placeholder="Enter Bank Type Others"
                                        v-model="form.bank_type_others"
                                    />
                                    <InputError :message="form.errors.bank_type_others" />
                                </div>
                                <DialogFooter>
                                    <Button type="submit" :disabled="form.processing">
                                        <span v-if="form.processing" class="flex items-center gap-1"
                                            ><LoaderCircle class="h-4 w-4 animate-spin" /> Updating...
                                        </span>
                                        <span class="flex items-center gap-1" v-else><Pen /> Update</span>
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </TableBody>
            </Table>
        </div>
        <AlertDialog v-model:open="deleteAlertDialogOpen">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle
                        >Are you absolutely sure you want to delete amount
                        {{ Number(selectedToDelete?.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }} with bank type of
                        {{ selectedToDelete?.bank_type?.name
                        }}<span v-if="selectedToDelete?.loan_type"
                            >and this has loan type of <span class="uppercase">{{ selectedToDelete?.loan_type?.name }}</span></span
                        >?</AlertDialogTitle
                    >
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently remove your data from our servers.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <Button
                        @click="deleteExpenseWallet(selectedToDelete.id)"
                        type="button"
                        :disabled="form.processing"
                        class="bg-red-500 text-white hover:bg-red-600"
                        ><span v-if="form.processing" class="flex items-center gap-1"><LoaderCircle class="animate-spin" /> Deleting...</span>
                        <span v-else>Yes, Delete</span></Button
                    >
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>
