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
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { LoaderCircle, Pen, PenBoxIcon, Plus, TrashIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expense categories',
        href: '/expense-categories',
    },
];

const { expenseCategories } = defineProps({
    expenseCategories: Object,
});

const tableHeads = ['ID', 'Name', 'Created At', 'Action'];

const form = useForm({
    name: '',
});

const modalOpen = ref(false);

const editModalOpen = ref(false);

const selectedExpenseCategory: any = ref(null);

const deleteAlertDialogOpen: any = ref(false);

const selectedToDelete: any = ref(null);

function submit() {
    form.post(route('expense-categories.store'), {
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

const deleteExpenseCategory = (id: number) => {
    form.delete(route('expense-categories.destroy', id), {
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
const handleEdit = (expenseCategory: any) => {
    form.name = expenseCategory.name;
    selectedExpenseCategory.value = expenseCategory;
    form.errors = {};
    editModalOpen.value = true;
};

const handleUpdate = () => {
    form.patch(route('expense-categories.update', selectedExpenseCategory.value.id), {
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

const handleAlertDialogOpen = (item: number) => {
    selectedToDelete.value = item;
    deleteAlertDialogOpen.value = true;
};
</script>

<template>
    <Head title="Expense Categories" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <div class="w-full">
                <Dialog v-model:open="modalOpen">
                    <Button class="float-end bg-blue-500 text-white hover:bg-blue-600" @click="handleOpenModal"
                        ><Plus /> Add Expense Categories</Button
                    >
                    <DialogContent class="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Add Expense Category</DialogTitle>
                        </DialogHeader>
                        <form @submit.prevent="submit">
                            <div class="grid gap-2 py-4">
                                <Label for="expense_name" class="text-right"> Expense Name </Label>
                                <Input
                                    id="expense_name"
                                    :class="form.errors.name && 'border-red-500'"
                                    placeholder="Enter Expense Name"
                                    class="col-span-3"
                                    v-model="form.name"
                                />
                                <InputError :message="form.errors.name" />
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
                    <TableRow v-for="(expenseCategory, index) in expenseCategories" :key="index">
                        <TableCell> {{ expenseCategory.id }}</TableCell>
                        <TableCell> {{ expenseCategory.name }}</TableCell>
                        <TableCell> {{ format(expenseCategory.created_at, 'MMM dd, yyyy hh:mm a') }}</TableCell>
                        <TableCell class="flex gap-2">
                            <Button class="bg-blue-500 text-white hover:bg-blue-600" @click="handleEdit(expenseCategory)"><PenBoxIcon /></Button>
                            <Button @click="handleAlertDialogOpen(expenseCategory)" class="bg-red-500 text-white hover:bg-red-600"
                                ><TrashIcon />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="expenseCategories?.length === 0">
                        <TableCell colspan="4" class="text-center">No expense categories found.</TableCell>
                    </TableRow>
                    <Dialog v-model:open="editModalOpen">
                        <DialogContent class="sm:max-w-[425px]">
                            <DialogHeader>
                                <DialogTitle>Editing {{ selectedExpenseCategory.name }}...</DialogTitle>
                            </DialogHeader>
                            <form @submit.prevent="handleUpdate">
                                <div class="grid gap-2 py-4">
                                    <Label for="expense_name" class="text-right"> Expense Name </Label>
                                    <Input
                                        id="expense_name"
                                        :class="form.errors.name && 'border-red-500'"
                                        placeholder="Enter Expense Name"
                                        class="col-span-3"
                                        v-model="form.name"
                                    />
                                    <InputError :message="form.errors.name" />
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
                    <AlertDialogTitle>Are you absolutely sure you want to delete this {{ selectedToDelete?.name }} loan type?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently remove your data from our servers.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <Button
                        @click="deleteExpenseCategory(selectedToDelete.id)"
                        type="button"
                        :disabled="form.processing"
                        class="bg-red-500 text-white hover:bg-red-600"
                        ><span v-if="form.processing" class="flex items-center gap-1"><LoaderCircle class="animate-spin" /> Deleting...</span>
                        <span v-else>Yes, Delete</span>
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>
