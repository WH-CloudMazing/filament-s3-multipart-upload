import { Uppy } from "@uppy/core"
import DragDrop from "@uppy/drag-drop"
import StatusBar from "@uppy/status-bar"
import AwsS3Multipart from "@uppy/aws-s3-multipart"

window.Uppy = Uppy
window.AwsS3Multipart = AwsS3Multipart
window.DragDrop = DragDrop
window.StatusBar = StatusBar

export default function uppy({state, maxFiles, maxSize, directory, companionUrl, csrfToken}) {
    return {
        uppy: null,
        state,
        uploadedFiles: [],

        bytesToSize (bytes) {
            const sizes = ["Bytes", "KB", "MB", "GB", "TB"]
            if (bytes === 0) return "n/a"
            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10)
            if (i === 0) return `${bytes} ${sizes[i]}`
            return `${(bytes / 1024 ** i).toFixed(1)} ${sizes[i]}`
        },

        init: function () {
            if (this.state) {
                this.uploadedFiles = [{ name: this.state, type: null, size: null }]
            }

            this.uppy = new Uppy({
                id: 'uppy',
                debug: true,
                restrictions: {
                    maxNumberOfFiles: maxFiles,
                    maxFileSize: maxSize,
                    minNumberOfFiles: 1
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}

                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = {
                            ...files[fileID],
                            name: `${directory}/${files[fileID].name}`,
                        }
                    })

                    return updatedFiles
                },
            });

            this.uppy
                .use(DragDrop, {
                    target: '.uppy__input',
                })
                .use(StatusBar, {
                    target: '.uppy__progress-bar',
                })
                .use(AwsS3Multipart, {
                    limit: 6,
                    companionUrl,
                    companionHeaders: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })

            this.uppy.on("file-added", file => {
                uppy.upload()
            });

            this.uppy.on("upload-success", (file, response) => {
                this.state = response.body.path

                if (maxFiles === 1) {
                    this.uploadedFiles = [file]
                } else {
                    this.uploadedFiles = [...this.uploadedFiles, file]
                }
            });
        },
    }
}
