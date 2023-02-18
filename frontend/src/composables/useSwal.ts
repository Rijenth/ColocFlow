import swal from "sweetalert";

export function useSwal(){
    function flash(title: string, message: string, type: string) {
      return swal(title, message, type);
    }

    return { flash };
}