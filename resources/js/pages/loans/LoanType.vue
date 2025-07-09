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
import { CheckCircle, LoaderCircle, Pen, PenBoxIcon, Plus, TrashIcon } from 'lucide-vue-next';
import { h, ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Loan types',
        href: '/loan-types',
    },
];

const { loanTypes } = defineProps({
    loanTypes: Object,
});

const tableHeads = ['ID', 'Name', 'Created At', 'Action'];

const form = useForm({
    name: '',
});

const modalOpen = ref(false);

const editModalOpen = ref(false);

const selectedLoan: any = ref(null);

const deleteAlertDialogOpen: any = ref(false);

const selectedToDelete: any = ref(null);

function submit() {
    form.post(route('loan-types.store'), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Created!', {
                description: success,
                duration: 3000,
                icon: h(CheckCircle, { className: 'text-green-500 w-5 h-5' }),
            });

            form.reset();

            modalOpen.value = false;
        },
    });
}

const deleteLoanType = (id: number) => {
    form.delete(route('loan-types.destroy', id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Deleted!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
                icon: h(CheckCircle, { className: 'text-green-500 w-5 h-5' }),
            });

            deleteAlertDialogOpen.value = false;
        },
    });
};
const handleEdit = (loanType: any) => {
    form.name = loanType.name;
    selectedLoan.value = loanType;
    form.errors = {};
    editModalOpen.value = true;
};

const handleUpdate = () => {
    form.patch(route('loan-types.update', selectedLoan.value.id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            editModalOpen.value = false;

            toast('Updated!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
                icon: h(CheckCircle, { className: 'text-green-500 w-5 h-5' }),
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
    <Head title="Loan types" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <div class="w-full">
                <Dialog v-model:open="modalOpen">
                    <Button class="float-end bg-blue-500 text-white hover:bg-blue-600" @click="handleOpenModal"><Plus /> Add Loan Types</Button>
                    <DialogContent class="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Add Loan Type</DialogTitle>
                        </DialogHeader>
                        <form @submit.prevent="submit">
                            <div class="grid gap-2 py-4">
                                <Label for="loan_name" class="text-right"> Loan Name </Label>
                                <Input
                                    id="loan_name"
                                    :class="form.errors.name && 'border-red-500'"
                                    placeholder="Enter Loan Name"
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
                    <TableRow v-for="(loanType, index) in loanTypes" :key="index">
                        <TableCell> {{ loanType.id }}</TableCell>
                        <TableCell> {{ loanType.name }}</TableCell>
                        <TableCell> {{ format(loanType.created_at, 'MMM dd, yyyy hh:mm a') }}</TableCell>
                        <TableCell class="flex gap-2">
                            <Button class="bg-blue-500 text-white hover:bg-blue-600" @click="handleEdit(loanType)"><PenBoxIcon /></Button>
                            <Button @click="handleAlertDialogOpen(loanType)" class="bg-red-500 text-white hover:bg-red-600"><TrashIcon /> </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="loanTypes?.length === 0">
                        <TableCell colspan="4" class="text-center">No loan types found.</TableCell>
                    </TableRow>
                    <Dialog v-model:open="editModalOpen">
                        <DialogContent class="sm:max-w-[425px]">
                            <DialogHeader>
                                <DialogTitle>Editing {{ selectedLoan.name }}...</DialogTitle>
                            </DialogHeader>
                            <form @submit.prevent="handleUpdate">
                                <div class="grid gap-2 py-4">
                                    <Label for="loan_name" class="text-right"> Loan Name </Label>
                                    <Input
                                        id="loan_name"
                                        :class="form.errors.name && 'border-red-500'"
                                        placeholder="Enter Loan Name"
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
                        @click="deleteLoanType(selectedToDelete.id)"
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
